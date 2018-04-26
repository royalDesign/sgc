<?php 
if(!empty($_POST['exec'])){

switch ($_POST['exec']) {
	
case 'save_search_satisfaction':	
{

$id = $_POST['id'];

$query = "SELECT * FROM sgc_".$user['customer_code']."_search_satisfactions WHERE id = :id";
$search = conecta()->prepare($query);
$search->bindValue(':id', $id);
$search->execute();
$row_up = $search->fetch(PDO::FETCH_ASSOC);


$data = array();
$data['title'] = trim(strip_tags($_POST['title']));
$data['status'] = trim(strip_tags($_POST['status']));
$data['number_questions'] = trim(strip_tags($_POST['number_questions']));

if($id){
$data['modified_date'] = date('Y-m-d H:i:s');
$data['modified_user'] = $user['id'];
}else{
$data['created_date'] = date('Y-m-d H:i:s');
$data['created_user'] = $user['id'];
}
$ret = sgc_save_db('sgc_'.$user['customer_code'].'_search_satisfactions', $data, $id);

if(!$ret['error_number']){
	if($ret['new_id']){
		$_POST['id'] = $ret['new_id'];
	}
	echo "<script>new PNotify({title: 'Sucesso!',text: 'Dados salvos com sucesso.',type: 'success'});</script>";	
}else{
  //print_r($ret);
	echo "<script>new PNotify({title: 'Error!',text: 'Não foi possível realizar esta operação.',type: 'error'});</script>";
}

if($id){
//atualização da linha do tempo
if($row_up['title'] != $data['title']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou esta categoria';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Título</strong></p> <strong>De</strong> '.$row_up['name'].' <br><strong>para</strong> '.$data['title'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_search_satisfactions_updates', $data_timeline, 0);
}



if($row_up['status'] != $data['status']){
$up_from = ($row_up['status'] == 1)?'Ativo':'Inativo';
$up_to = ($data['status'] == 1)?'Ativo':'Inativo';
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou esta categoria';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Status</strong></p> <strong>De</strong> '.$up_from.' <br><strong>para</strong> '.$up_to;
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_search_satisfactions_updates', $data_timeline, 0);
}

}//END IF ID


    
}break;


case 'save_questions':
  {
    
$query  = "DELETE FROM sgc_".$user['customer_code']."_search_satisfactions_questions WHERE search_satisfactions_id = :id";
$exec   = conecta()->prepare($query);
$exec->bindValue(':id',$_POST['id']);
$exec->execute();

for ($x=1; $x <= $_POST['qtd_questions'] ; $x++){ 
  
  $data = array();
  $data['content'] = $_POST['content_'.$x];
  $data['created_user'] = $user['id'];
  $data['created_date'] = date('Y-m-d H:i:s');
  $data['search_satisfactions_id'] = $_POST['id'];

  $ret = sgc_save_db('sgc_'.$user['customer_code'].'_search_satisfactions_questions',$data,0);

}//end FOR salvando questões

  if(!$ret['error_number']){
  echo "<script>new PNotify({title: 'Sucesso!',text: 'Perguntas salvas com sucesso.',type: 'success'});</script>";
  }

  }
  break;




case 'delete_question':
{

//print_r($_POST);

//deletando o selecionado
$query  = "DELETE FROM sgc_".$user['customer_code']."_search_satisfactions_questions WHERE id = :id";
$exec   = conecta()->prepare($query);
$exec->bindValue(':id',$_POST['question_id']);
$exec->execute();

$query = "UPDATE sgc_".$user['customer_code']."_search_satisfactions SET number_questions = number_questions -1 WHERE id = :id";
$exec = conecta()->prepare($query);
$exec->bindValue(':id', $_POST['id']);
$exec->execute();






}break;
	
	
}
}


$query = "SELECT a.*,u.name AS name_created,um.name AS name_modified FROM sgc_".$user['customer_code']."_search_satisfactions AS a LEFT JOIN users AS u ON u.id = a.created_user LEFT JOIN users AS um ON um.id = a.modified_user WHERE a.id = :id";
$search = conecta()->prepare($query);
$search->bindValue(':id', $_POST['id']);
$search->execute();
$row = $search->fetch(PDO::FETCH_ASSOC);


//timeline
$query = "SELECT a.*,u.name AS name_user FROM sgc_".$user['customer_code']."_search_satisfactions_updates AS a LEFT JOIN users AS u ON u.id = a.created_user WHERE a.item_id = :id ORDER BY a.id DESC";
$search = conecta()->prepare($query);
$search->bindValue(':id', $_POST['id']);
$search->execute();
$rows_timeline = $search->fetchAll(PDO::FETCH_ASSOC);

?>
<section class="content-header">
      <h1>Pesquisa de satisfação</h1>
      <ol class="breadcrumb">
        <li><a href="javascript:void();" onclick="open_target('target=home');"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void();" onclick="open_target('target=search_satisfactions');">Categorias dos produtos</a></li>
        <li class="active">Pesquisa de satisfação</li>
      </ol>
    </section>

<!-- Main content -->
<section class="content">
<div class="row">


        <!-- /.col -->
        <div class="col-md-8">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">              
              <li class="active"><a href="#cadastro" data-toggle="tab">Cadastro</a></li>
              <li><a href="#timeline" data-toggle="tab">Atualizações</a></li>              
            </ul>
            <div class="tab-content">




<div class="tab-pane active" id="cadastro">
<form role="form" action="" method="POST" name="form_main" enctype="multipart/form-data">
 <input type="hidden" name="id" id="id" value="<?php echo $row['id'];?>">
    <div class="box-body">
        <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="title">Nome:</label>
                <input type="text"  name="title" autocomplete="off"  class="form-control" id="title" placeholder="Título da pesquisa" value="<?php echo $row['title']?>">
            </div>
        </div>
        
        </div><!--END ROW-->


        <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="title">Quantidade de perguntas</label>
                <input type="number" min="1"  name="number_questions" autocomplete="off"  class="form-control" id="number_questions" value="<?php echo $row['number_questions']?>">
            </div>
        </div>


        <div class="col-sm-5">
            <div class="form-group">
                <label for="title">Tipo de resposta</label>
                <select class="form-control" name="type_response" id="type_response">
                  <option value="0">-- Selecione --</option>
                  <option value="1">Única escolha</option>
                  <option value="2">Múltipla escolha</option>
                  <option value="3">Texto simples</option>
                  <option value="4">Texto longo</option>
                  
                </select>
            </div>
        </div>


        <div class="col-sm-3">
            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" class="form-control">
                     <option value="">--Selecione--</option>
                    <option <?php echo ($row['status'] == '1')?'selected':''; ?> value="1">Ativo</option>
                    <option <?php echo ($row['status'] == '0')?'selected':''; ?> value="0">Inativo</option>
                </select>
            </div>
        </div>


    </div><!--END ROW-->
       
     <div class="row">
                <div class="col-sm-6">
                  <button type="button" name="sgc_save" id="sgc_save" onclick="sgc_save_main($(this));" class="btn btn-primary btn_action"><i class="fa fa-check"></i> Salvar</button>
                </div>
                <div class="col-sm-6">
              <button type="button" onclick="open_target('target=search_satisfactions', $(this));" class="btn btn-default pull-right rr"> Voltar</button>
                </div>
                  
              </div>

                  
</div><!--END box body-->
           
            
            </form>
              </div><!--END PANE CADASTRO -->


              
              <div class="tab-pane" id="timeline">
                <!-- The timeline -->
                <ul class="timeline timeline-inverse">

<?php if(count($rows_timeline) == 0): ?>

	<li>
	 <i class="fa fa-clock-o bg-gray"></i>

	 <div class="timeline-item">
<span class="time"><i class="fa fa-clock-o"></i> <?php echo date('d/m/Y H:i:s') ?></span>

<h3 class="timeline-header"><span style="color: #3c8dbc";>Sistema</span> Esta categoria não possui atualizações</h3>

                      
</div>


    </li>

<?php else: ?>
<?php foreach ($rows_timeline as $row_timeline): ?>
<li>
  <?php echo $row_timeline['icon'] ?>

  <div class="timeline-item">
<span class="time"><i class="fa fa-clock-o"></i> <?php echo sgc_date_format($row_timeline['created_date'], 'd/m/Y H:i') ?></span>

<h3 class="timeline-header"><span style="color: #3c8dbc";><?php echo $row_timeline['name_user']; ?></span> <?php echo $row_timeline['title']; ?></h3>

<div class="timeline-body">
  <?php echo $row_timeline['content']; ?>
</div>
                      
</div>

</li>

<?php endforeach; ?>

<?php endif; ?>
                  
                </ul>
              </div>
              <!-- END PANE TIMELINE -->

              





            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->

<?php if ($row['id']): ?>
<div class="col-md-4">
  	<div class="box box-primary">
    	<div class="box-body box-profile">
    		<div class="box-header with-border">
             <h3 class="box-title">Resumo</h3>
            </div>


            <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  Criado por <br><a><?php echo $row['name_created']; ?></a> em <a><?php echo sgc_date_format($row['created_date'],'d/m/Y H:i'); ?></a>
                </li>

		<?php if ($row['modified_date']): ?>
                <li class="list-group-item">
                  Atualizado por<br> <a><?php echo $row['name_modified']; ?></a> em <a><?php echo sgc_date_format($row['modified_date'],'d/m/Y H:i'); ?></a>
                </li>     
      <?php endif; ?>           
              </ul>						
          
			
    	</div>            
  	</div>
</div>
<?php endif; ?>
</div><!-- /.row 7/5 -->




<?php if($row['id']): ?>
  <div class="content box box-primary">
    
  
<div class="row">
<div class="col-md-6">
<form role="form" action="" method="POST" name="form_questions" enctype="multipart/form-data">
 <input type="hidden" name="id" id="id" value="<?php echo $row['id'];?>">
 <input type="hidden" name="qtd_questions" id="qtd_questions" value="<?php echo $row['number_questions'];?>"> 

      <h4>Edição de perguntas</h4>

      <?php for($x=1;$x<=$row['number_questions'];$x++):?>
        <?php

//Consultar as perguntas
        $xy = $x-1;
$query = "SELECT a.content,a.created_date,a.id FROM sgc_".$user['customer_code']."_search_satisfactions_questions AS a WHERE a.search_satisfactions_id = :id ORDER BY a.id ASC LIMIT ".$xy.",".$row['number_questions'];
$search = conecta()->prepare($query);
$search->bindValue(':id', $row['id']);
$search->execute();
$res = $search->fetch(PDO::FETCH_ASSOC);

//$error = $search->errorInfo();
//print_r($error);




        ?>

  <div class="row">
  <div class="col-sm-9">
      <div class="form-group">
          <label for="title">Pergunta - <?php echo $x;?></label>
          <textarea  name="content_<?php echo $x;?>" id="content_<?php echo $x;?>" class="form-control"><?php echo $res['content']?></textarea>
        <a class="text-info" href="javascript:void(0)" onclick="sgc_delete_item('item_content_<?php echo $res['id']?>');">Excluir <i class="fa fa-trash"></i></a>




        <p style="display: none;" class="item_content_<?php echo $res['id']?>"><a href="javascript:void(0)" onclick="sgc_delete_item_confirmed('<?php echo $res['id']?>',$(this));" class="text-danger">Confirmar exclusão</a>
      
      </div>
  </div>
</div><!--END ROW-->
    <?php endfor;?>


    <div class="row">
      <div class="col-sm-6">
        <button type="button" name="sgc_save" id="sgc_save" onclick="sgc_save_questions($(this));" class="btn btn-primary btn_action"><i class="fa fa-check"></i> Salvar</button>
      </div>      
  </div>


  </form>


</div>

<div class="col-md-6">
  <h4>Pré-visualização da pesquisa</h4>
</div>


</div><!--Linha GERAL-->

</div>
<?php endif;?>

    </section>

     <!-- /.content -->
  </div>

  <script>
  	
function sgc_save_main(btn){

var form    		= $('form[name="form_main"]');
var title    		= $('#title');
var status   	  	= $('#status');
var description   	= $('#description');
var params 			= 'target=search_satisfaction&exec=save_search_satisfaction&'+form.serialize();

if(valid_fild('required',title) && valid_fild('select',status)){
open_target(params,btn);
}
}


function sgc_save_questions(btn){

var form        = $('form[name="form_questions"]');
var params      = 'target=search_satisfaction&exec=save_questions&'+form.serialize();

open_target(params,btn);

}


function sgc_delete_item(item_toggle){

  $('.'+item_toggle).fadeIn('fast');

}

function sgc_delete_item_confirmed(question_id,btn) {
  
  var id = $('#id').val();

  open_target('target=search_satisfaction&exec=delete_question&id='+id+'&question_id='+question_id,btn);
}
  </script>

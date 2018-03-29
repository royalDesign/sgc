<?php 
if(!empty($_POST['exec'])){

switch ($_POST['exec']) {
	
case 'save_product':	
{
$id = $_POST['id'];

$query = "SELECT * FROM sgc_".$user['customer_code']."_products WHERE id = :id";
$search = conecta()->prepare($query);
$search->bindValue(':id', $id);
$search->execute();
$row_up = $search->fetch(PDO::FETCH_ASSOC);


$data = array();
$data['name'] = trim(strip_tags($_POST['name']));
$data['description'] = trim(strip_tags($_POST['description']));
$data['status'] = trim(strip_tags($_POST['status']));
$data['quantity_stock'] = trim(strip_tags($_POST['quantity_stock']));
$data['category_id'] = trim(strip_tags($_POST['category_id']));
$data['amount'] = sgc_currency($_POST['amount']);
if($id){
$data['modified_date'] = date('Y-m-d H:i:s');
$data['modified_user'] = $user['id'];
}else{
$data['created_date'] = date('Y-m-d H:i:s');
$data['created_user'] = $user['id'];
}
$ret = sgc_save_db('sgc_'.$user['customer_code'].'_products', $data, $id);

if(!$ret['error_number']){
	if($ret['new_id']){
		$_POST['id'] = $ret['new_id'];
	}
	echo "<script>new PNotify({title: 'Sucesso!',text: 'Dados salvos com sucesso.',type: 'success'});</script>";	
}else{
	echo "<script>new PNotify({title: 'Error!',text: 'Não foi possível realizar esta operação.',type: 'error'});</script>";
}

if($id){
//atualização da linha do tempo
if($row_up['name'] != $data['name']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este produto';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Nome</strong></p> <strong>De</strong> '.$row_up['name'].' <br><strong>para</strong> '.$data['name'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_products_updates', $data_timeline, 0);
}

if($row_up['description'] != $data['description']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este produto';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Descrição</strong></p> <strong>De</strong> '.$row_up['description'].' <br><strong>para</strong> '.$data['description'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_products_updates', $data_timeline, 0);
}

if($row_up['status'] != $data['status']){
	$up_from = ($row_up['status'] == 1)?'Ativo':'Inativo';
	$up_to = ($data['status'] == 1)?'Ativo':'Inativo';
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este produto';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Status</strong></p> <strong>De</strong> '.$up_from.' <br><strong>para</strong> '.$up_to;
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_products_updates', $data_timeline, 0);
}

if($row_up['quantity_stock'] != $data['quantity_stock']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este produto';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Estoque</strong></p> <strong>De</strong> '.$row_up['quantity_stock'].' <br><strong>para</strong> '.$data['quantity_stock'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_products_updates', $data_timeline, 0);
}

if($row_up['amount'] != $data['amount']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este produto';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Preço</strong></p> <strong>De </strong>R$ '.number_format($row_up['amount'],2,',','.').' <br><strong>para </strong>R$ '.number_format($data['amount'],2,',','.');
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_products_updates', $data_timeline, 0);
}

}//END IF ID


    
}break;
	
	
}
}


$query = "SELECT a.*,u.name AS name_created,um.name AS name_modified FROM sgc_".$user['customer_code']."_products AS a LEFT JOIN users AS u ON u.id = a.created_user LEFT JOIN users AS um ON um.id = a.modified_user WHERE a.id = :id";
$search = conecta()->prepare($query);
$search->bindValue(':id', $_POST['id']);
$search->execute();
$row = $search->fetch(PDO::FETCH_ASSOC);


//timeline
$query = "SELECT a.*,u.name AS name_user FROM sgc_".$user['customer_code']."_products_updates AS a LEFT JOIN users AS u ON u.id = a.created_user WHERE a.item_id = :id ORDER BY a.id DESC";
$search = conecta()->prepare($query);
$search->bindValue(':id', $_POST['id']);
$search->execute();
$rows_timeline = $search->fetchAll(PDO::FETCH_ASSOC);

//Popula Categorias
$query = "SELECT id,name FROM sgc_".$user['customer_code']."_products_categories WHERE status = 1 ORDER BY name";
$search = conecta()->prepare($query);
$search->execute();
$rows_categories = $search->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="content-header">
      <h1>Produto</h1>
      <ol class="breadcrumb">
        <li><a href="javascript:void();" onclick="open_target('target=home');"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void();" onclick="open_target('target=products');">Gestão de produtos</a></li>
        <li class="active">Produto</li>
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
    <div class="box-body">
        <div class="row">
        <div class="col-sm-9">
            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text"  name="name" autocomplete="off"  class="form-control" id="name" placeholder="Nome completo" value="<?php echo $row['name']?>">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="status">Status</label>
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
            <div class="form-group">
                <label for="category_id">Categoria</label>
                <select name="category_id" id="category_id" class="form-control">
                     <option value="">--Selecione--</option>
                     <?php foreach ($rows_categories as $row_categories):?>
  <option <?php echo ($row['category_id'] == $row_categories['id'])?'selected':''; ?> value="<?php echo $row_categories['id'];?>"><?php echo $row_categories['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

          <div class="col-sm-3">
            <div class="form-group">
                <label for="quantity_stock">Estoque</label>
<input type="number" class="form-control" id="quantity_stock" name="quantity_stock" value="<?php echo $row['quantity_stock'];?>">
            </div>
          </div>
          <div class="col-sm-3">            
            <div class="form-group">
                <label for="amount">Preço</label>
<input type="text" class="form-control currency" id="amount" name="amount" value="<?php echo number_format($row['amount'],2,',','.');?>">
            </div>
          </div>
        </div><!--END ROW-->

        <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="description">Descrição</label>
                <textarea name="description" class="form-control" id="description" cols="30" rows="5"><?php echo $row['description']; ?></textarea>

                <input type="hidden" name="id" id="id" value="<?php echo $_POST['id'];?>">
            </div>
        </div>
        
        </div><!--END ROW-->

       

   
<div class="row">
                <div class="col-sm-6">
                  <button type="button" name="sgc_save" id="sgc_save" onclick="sgc_save_main($(this));" class="btn btn-primary btn_action"><i class="fa fa-check"></i> Salvar</button>
                </div>
                <div class="col-sm-6">
              <button type="button" onclick="open_target('target=products', $(this));" class="btn btn-default pull-right rr"> Voltar</button>
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

<h3 class="timeline-header"><span style="color: #3c8dbc";>Sistema</span> Este produto não possui atualizações</h3>

                      
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

      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>

  <script>

$(function() {
    $('.currency').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
});



function sgc_save_main(btn){

var form    		= $('form[name="form_main"]');
var name    		= $('#name');
var status   	  	= $('#status');
var description   	= $('#description');
var params 			= 'target=product&exec=save_product&'+form.serialize();

if(valid_fild('required',name) && valid_fild('select',status)){
open_target(params,btn);
}
}
  </script>

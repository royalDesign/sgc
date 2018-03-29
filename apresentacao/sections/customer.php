<?php 
if(!empty($_POST['exec'])){

switch ($_POST['exec']) {
	
case 'save_customer':	
{

$id = $_POST['id'];

$query = "SELECT * FROM sgc_".$user['customer_code']."_customers WHERE id = :id";
$search = conecta()->prepare($query);
$search->bindValue(':id', $id);
$search->execute();
$row_up = $search->fetch(PDO::FETCH_ASSOC);


$data = array();
$data['name'] = trim(strip_tags($_POST['name']));
$data['status'] = trim(strip_tags($_POST['status']));
$data['birth_date']  = sgc_date_format($_POST['birth_date'],'Y-m-d');
$data['type']  = trim(strip_tags($_POST['type']));
$data['genre']  = trim(strip_tags($_POST['genre']));
$data['email']  = trim(strip_tags($_POST['email']));
$data['phone']  = trim(strip_tags($_POST['phone']));
$data['cellphone']  = trim(strip_tags($_POST['cellphone']));
$data['cellphone_secondary']  = trim(strip_tags($_POST['cellphone_secondary']));
$data['cpf']  = trim(strip_tags($_POST['cpf']));
$data['rg']  = trim(strip_tags($_POST['rg']));
$data['address_zipcode']  = trim(strip_tags($_POST['address_zipcode']));
$data['address_street']  = trim(strip_tags($_POST['address_street']));
$data['address_city']  = trim(strip_tags($_POST['address_city']));
$data['address_state']  = trim(strip_tags($_POST['address_state']));
$data['address_district']  = trim(strip_tags($_POST['address_district']));
$data['address_complement']  = trim(strip_tags($_POST['address_complement']));
$data['address_number']  = trim(strip_tags($_POST['address_number']));


//data PJ
$data['social_name']  = trim(strip_tags($_POST['social_name']));
$data['fantasy_name']  = trim(strip_tags($_POST['fantasy_name']));
$data['cnpj']  = trim(strip_tags($_POST['cnpj']));
$data['state_registration']  = trim(strip_tags($_POST['state_registration']));
$data['municipal_registration']  = trim(strip_tags($_POST['municipal_registration']));


if($id){
$data['modified_date'] = date('Y-m-d H:i:s');
$data['modified_user'] = $user['id'];
}else{
$data['created_date'] = date('Y-m-d H:i:s');
$data['created_user'] = $user['id'];
}
$ret = sgc_save_db('sgc_'.$user['customer_code'].'_customers', $data, $id);

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

if($row_up['type'] != $data['type']){
$up_from = ($row_up['type'] == 1)?'Pessoa física':'Pessoa jurídica';
$up_to = ($data['type'] == 1)?'Pessoa física':'Pessoa jurídica';
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Tipo</strong></p> <strong>De</strong> '.$up_from.' <br><strong>para</strong> '.$up_to;
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

if($row_up['name'] != $data['name']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Nome</strong></p> <strong>De</strong> '.$row_up['name'].' <br><strong>para</strong> '.$data['name'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}


if($row_up['status'] != $data['status']){
	$up_from = ($row_up['status'] == 1)?'Ativo':'Inativo';
	$up_to = ($data['status'] == 1)?'Ativo':'Inativo';
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Status</strong></p> <strong>De</strong> '.$up_from.' <br><strong>para</strong> '.$up_to;
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

if($row_up['rg'] != $data['rg']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>RG</strong></p> <strong>De</strong> '.$row_up['rg'].' <br><strong>para</strong> '.$data['rg'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

if($row_up['cpf'] != $data['cpf']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>CPF</strong></p> <strong>De</strong> '.$row_up['cpf'].' <br><strong>para</strong> '.$data['cpf'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}


if($row_up['social_name'] != $data['social_name']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Razão social</strong></p> <strong>De</strong> '.$row_up['social_name'].' <br><strong>para</strong> '.$data['social_name'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

if($row_up['fantasy_name'] != $data['fantasy_name']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Nome fantasia</strong></p> <strong>De</strong> '.$row_up['fantasy_name'].' <br><strong>para</strong> '.$data['fantasy_name'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

if($row_up['cnpj'] != $data['cnpj']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>CNPJ</strong></p> <strong>De</strong> '.$row_up['cnpj'].' <br><strong>para</strong> '.$data['cnpj'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

if($row_up['state_registration'] != $data['state_registration']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Inscrição estadual</strong></p> <strong>De</strong> '.$row_up['state_registration'].' <br><strong>para</strong> '.$data['state_registration'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

if($row_up['municipal_registration'] != $data['municipal_registration']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Inscrição municipal</strong></p> <strong>De</strong> '.$row_up['municipal_registration'].' <br><strong>para</strong> '.$data['municipal_registration'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

if($row_up['genre'] != $data['genre']){
  $up_from = ($row_up['genre'] == 'M')?'Masculino':'Feminino';
  $up_to = ($data['genre'] == 'M')?'Masculino':'Feminino';
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Gênero</strong></p> <strong>De</strong> '.$up_from.' <br><strong>para</strong> '.$up_to;
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

if($row_up['birth_date'] != $data['birth_date']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Nascimento</strong></p> <strong>De</strong> '.sgc_date_format($row_up['birth_date'],'d/m/Y').' <br><strong>para</strong> '.sgc_date_format($data['birth_date'],'d/m/Y');
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

if($row_up['email'] != $data['email']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>E-mail</strong></p> <strong>De</strong> '.$row_up['email'].' <br><strong>para</strong> '.$data['email'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

if($row_up['phone'] != $data['phone']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Tel. Residencial</strong></p> <strong>De</strong> '.$row_up['phone'].' <br><strong>para</strong> '.$data['phone'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

if($row_up['cellphone'] != $data['cellphone']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Tel. Principal</strong></p> <strong>De</strong> '.$row_up['cellphone'].' <br><strong>para</strong> '.$data['cellphone'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

if($row_up['cellphone_secondary'] != $data['cellphone_secondary']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Tel. Secundario</strong></p> <strong>De</strong> '.$row_up['cellphone_secondary'].' <br><strong>para</strong> '.$data['cellphone_secondary'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

if($row_up['address_zipcode'] != $data['address_zipcode']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>CEP</strong></p> <strong>De</strong> '.$row_up['address_zipcode'].' <br><strong>para</strong> '.$data['address_zipcode'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}


if($row_up['address_street'] != $data['address_street']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Rua</strong></p> <strong>De</strong> '.$row_up['address_street'].' <br><strong>para</strong> '.$data['address_street'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

if($row_up['address_city'] != $data['address_city']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Cidade</strong></p> <strong>De</strong> '.$row_up['address_city'].' <br><strong>para</strong> '.$data['address_city'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}


if($row_up['address_state'] != $data['address_state']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Estado</strong></p> <strong>De</strong> '.$row_up['address_state'].' <br><strong>para</strong> '.$data['address_state'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

if($row_up['address_district'] != $data['address_district']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Bairro</strong></p> <strong>De</strong> '.$row_up['address_district'].' <br><strong>para</strong> '.$data['address_district'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

if($row_up['address_number'] != $data['address_number']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Número</strong></p> <strong>De</strong> '.$row_up['address_number'].' <br><strong>para</strong> '.$data['address_number'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

if($row_up['address_complement'] != $data['address_complement']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este cliente';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Complemento</strong></p> <strong>De</strong> '.$row_up['address_complement'].' <br><strong>para</strong> '.$data['address_complement'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_customers_updates', $data_timeline, 0);
}

}//END IF ID


    
}break;
	
	
}
}


$query = "SELECT a.*,u.name AS name_created,um.name AS name_modified FROM sgc_".$user['customer_code']."_customers AS a LEFT JOIN users AS u ON u.id = a.created_user LEFT JOIN users AS um ON um.id = a.modified_user WHERE a.id = :id";
$search = conecta()->prepare($query);
$search->bindValue(':id', $_POST['id']);
$search->execute();
$row = $search->fetch(PDO::FETCH_ASSOC);


//timeline
$query = "SELECT a.*,u.name AS name_user FROM sgc_".$user['customer_code']."_customers_updates AS a LEFT JOIN users AS u ON u.id = a.created_user WHERE a.item_id = :id ORDER BY a.id DESC";
$search = conecta()->prepare($query);
$search->bindValue(':id', $_POST['id']);
$search->execute();
$rows_timeline = $search->fetchAll(PDO::FETCH_ASSOC);

//Popula Categorias
$query = "SELECT id,name FROM sgc_".$user['customer_code']."_customers_categories WHERE status = 1 ORDER BY name";
$search = conecta()->prepare($query);
$search->execute();
$rows_categories = $search->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="content-header">
      <h1>Cliente</h1>
      <ol class="breadcrumb">
        <li><a href="javascript:void();" onclick="open_target('target=home');"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void();" onclick="open_target('target=customers');">Gestão de Clientes</a></li>
        <li class="active">Cliente</li>
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
        <div class="col-sm-6">
          <label>
              <input type="radio" name="type" onclick="type_customer($(this));" id="type1" value="1" <?php echo ($row['type'] == 1)?'checked':'';?>>
               Pessoa física
          </label>
        </div>
        <div class="col-sm-6">
          <label>
              <input type="radio" name="type" onclick="type_customer($(this));" id="type2" value="2" <?php echo ($row['type'] == 2)?'checked':'';?>>
               Pessoa jurídica
          </label>
        </div>
      </div>
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
     <div class="col-sm-3">
            <div class="form-group">
                <label for="genre">Gênero</label>
                <select name="genre" id="genre" class="form-control">
                    <option value="M" <?php echo ($row['genre'] == 'M')?'selected="selected"':'';?>>M</option>
                    <option value="F" <?php echo ($row['genre'] == 'F')?'selected="selected"':'';?>>F</option>
                </select>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <label for="birth_date">Nascimento</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
          <input type="text" value="<?php echo sgc_date_format($row['birth_date'], 'd/m/Y') ;?>" OnKeyPress="return sgc_masc_fild(event, this, '##/##/####');"  name="birth_date" id="birth_date" class="form-control pull-right">
                </div>
            </div>
        </div>


        <div class="col-sm-6">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="text"  name="email" autocomplete="off"  class="form-control" id="email" placeholder="E-mail" value="<?php echo $row['email']?>">
            </div>
        </div>

   </div><!--END ROW--> 

<div class="pf" style="<?php echo ($row['type'] == 1)?'display: block':'display: none';?>">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
                <label for="rg">RG</label>
                <input class="form-control" value="<?php echo $row['rg'] ?>" OnKeyPress="return sgc_masc_fild(event, this, '########-##');" type="text" id="rg" name="rg" />
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="cpf">CPF</label>
                <input class="form-control" value="<?php echo $row['cpf'] ?>" OnKeyPress="return sgc_masc_fild(event, this, '###.###.###-##');" type="text" id="cpf" name="cpf" />
            </div>
        </div>
        </div><!--END ROW-->
</div><!--END campos PF-->

<div class="pj" style="<?php echo ($row['type'] == 2)?'display: block':'display: none';?>">
  <div class="row">
    <div class="col-sm-6">
        <div class="form-group">
          <label for="social_name">Razão social</label>
          <input type="text"  name="social_name" autocomplete="off"  class="form-control" id="social_name" value="<?php echo $row['social_name']?>">
        </div>
    </div>

    <div class="col-sm-6">
      <div class="form-group">
        <label for="fantasy_name">Nome fantasia</label>
        <input type="text"  name="fantasy_name" autocomplete="off"  class="form-control" id="fantasy_name" value="<?php echo $row['fantasy_name']?>">
      </div>
    </div>
        </div><!--END ROW-->

      <div class="row">

        <div class="col-sm-4">
            <div class="form-group">
                <label for="cnpj">CNPJ</label>
                <input class="form-control" value="<?php echo $row['cnpj'] ?>" OnKeyPress="return sgc_masc_fild(event, this, '##.###.###/####-##');" type="text" id="cnpj" name="cnpj" />
            </div>
        </div>

          <div class="col-sm-4">
            <div class="form-group">
              <label for="state_registration">Inscrição estadual</label>
              <input type="text"  name="state_registration" autocomplete="off"  class="form-control" id="state_registration" value="<?php echo $row['state_registration']?>">
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              <label for="municipal_registration">Inscrição municipal</label>
              <input type="text"  name="municipal_registration" autocomplete="off"  class="form-control" id="municipal_registration" value="<?php echo $row['municipal_registration']?>">
            </div>
          </div>
      </div><!--END ROW-->

</div><!--END campos PJ-->


   <div class="row">
          <div class="col-sm-4">           
            <div class="form-group">
                <label for="phone">Tel. Residencial</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                    </div>
<input type="text"  placeholder="(XX)xxxx-xxxx" value="<?php echo $row['phone'] ?>" class="form-control" id="phone" name="phone" OnKeyPress="return sgc_masc_fild(event, this, '(##)####-####');">
                </div>
            </div>
        </div>

          <div class="col-sm-4">           
            <div class="form-group">
                <label for="cellphone">Tel. Principal</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                    </div>
<input type="text"  placeholder="(XX)xxxxx-xxxx" value="<?php echo $row['cellphone'] ?>" class="form-control" id="cellphone" name="cellphone" OnKeyPress="return sgc_masc_fild(event, this, '(##)#####-####');">
                </div>
            </div>
        </div>
                
        <div class="col-sm-4">
            <div class="form-group">
                <label for="cellphone_secondary">Tel. Secundario</label>
                <div class="input-group">
                    <div class="input-group-addon">
                    <i class="fa fa-phone"></i>
                    </div>
                <input type="text" class="form-control" placeholder="(XX)xxxxx-xxxx" value="<?php echo $row['cellphone_secondary'] ?>"  name="cellphone_secondary" id="cellphone_secondary" OnKeyPress="return sgc_masc_fild(event, this, '(##)#####-####');">
                </div>
            </div>
        </div>
        </div><!--END ROW-->


   <div class="row">
        <div class="col-sm-2">
            <div class="form-group">
                <label for="address_zipcode">Cep</label>
                <input class="form-control" value="<?php echo $row['address_zipcode'] ?>" onblur="sgc_get_address();" OnKeyPress="return sgc_masc_fild(event, this, '#####-###');" type="text" id="address_zipcode" name="address_zipcode" />
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="address_street">Rua</label>
                <input class="form-control"  type="text" value="<?php echo $row['address_street'] ?>" placeholder="Rua" id="address_street" name="address_street" />
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="address_district">Bairro</label>
                <input class="form-control"  type="text" value="<?php echo $row['address_district'] ?>" placeholder="Bairro" id="address_district" name="address_district" />
            </div>
        </div>

         <div class="col-sm-2">
            <div class="form-group">
                <label>Numero</label>
                <input class="form-control"  type="text" value="<?php echo $row['address_number'] ?>" id="address_number" name="address_number" maxlength="6" OnKeyPress="return sgc_masc_fild(event, this, '######');"/>
            </div>
        </div>
      </div><!--END ROW-->
<div class="row">
        <div class="col-sm-5">
            <div class="form-group">
                <label for="address_city">Cidade</label>
                <input class="form-control"  type="text" value="<?php echo $row['address_city'] ?>" placeholder="Nome da Cidade" id="address_city" name="address_city" />
            </div>
        </div>
        
        <div class="col-sm-2">
            <div class="form-group">
                <label for="address_state">Estado</label>
                <input class="form-control" value="<?php echo $row['address_state'] ?>"  type="text" id="address_state" name="address_state" />
            </div>
        </div>


<div class="col-sm-5">
            <div class="form-group">
                <label for="address_complement">Complemento</label>
                <input class="form-control" value="<?php echo $row['address_complement'] ?>"  type="text" placeholder="Ex: predio, apartamento, andar etc..." id="address_complement" name="address_complement" />
            </div>
        </div>
  </div><!--END ROW-->

<div class="row">
  <div class="col-sm-6">
    <input type="hidden" name="id" id="id" value="<?php echo $_POST['id'];?>">
    <button type="button" name="sgc_save" id="sgc_save" onclick="sgc_save_main($(this));" class="btn btn-primary btn_action"><i class="fa fa-check"></i> Salvar</button>
  </div>
  <div class="col-sm-6">
    <button type="button" onclick="open_target('target=customers', $(this));" class="btn btn-default pull-right rr"> Voltar</button>
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

<h3 class="timeline-header"><span style="color: #3c8dbc";>Sistema</span> Este Cliente não possui atualizações</h3>

                      
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



$('#birth_date').datepicker({
autoclose: true,
language: 'br'
});

});



function sgc_save_main(btn){

var form    		= $('form[name="form_main"]');
var name    		= $('#name');
var status   	  	= $('#status');
var description   	= $('#description');
var params 			= 'target=customer&exec=save_customer&'+form.serialize();

if(valid_fild('required',name) && valid_fild('select',status)){
open_target(params,btn);
}
}


function type_customer(obj){

  var type = obj.val();

  if(type == 1){
    $('.pj').slideUp('slow', function() {
      $('.pf').slideDown('slow');
    });
  }else{
    $('.pf').slideUp('slow', function() {
      $('.pj').slideDown('slow');
    });
  }


}
  </script>


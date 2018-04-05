<?php 
if(!empty($_POST['exec'])){

switch ($_POST['exec']) {
	
case 'save_contributor':	
{

$id = $_POST['id'];

$query = "SELECT * FROM sgc_".$user['customer_code']."_contributors WHERE id = :id";
$search = conecta()->prepare($query);
$search->bindValue(':id', $id);
$search->execute();
$row_up = $search->fetch(PDO::FETCH_ASSOC);


$data = array();
$data['name'] = trim(strip_tags($_POST['name']));
$data['status'] = trim(strip_tags($_POST['status']));
$data['birth_date']  = sgc_date_format($_POST['birth_date'],'Y-m-d');
$data['genre']  = trim(strip_tags($_POST['genre']));
$data['email']  = trim(strip_tags($_POST['email']));
$data['phone']  = trim(strip_tags($_POST['phone']));
$data['cellphone']  = trim(strip_tags($_POST['cellphone']));
$data['cellphone_secondary']  = trim(strip_tags($_POST['cellphone_secondary']));
$data['cpf']  = trim(strip_tags($_POST['cpf']));
$data['rg']  = trim(strip_tags($_POST['rg']));
$data['rg_expedition_date']  = sgc_date_format($_POST['rg_expedition_date'],'Y-m-d');
$data['rg_emitter']  = trim(strip_tags($_POST['rg_emitter']));
$data['cnh']  = trim(strip_tags($_POST['cnh']));
$data['cnh_due']  = sgc_date_format($_POST['cnh_due'],'Y-m-d');
$data['first_cnh']  = sgc_date_format($_POST['first_cnh'],'Y-m-d');
$data['cnh_category']  = trim(strip_tags($_POST['cnh_category']));
$data['title_voter_zone']  = trim(strip_tags($_POST['title_voter_zone']));
$data['title_voter_session']  = trim(strip_tags($_POST['title_voter_session']));
$data['title_voter']  = trim(strip_tags($_POST['title_voter']));
$data['pis_nis_pasep']  = trim(strip_tags($_POST['pis_nis_pasep']));
$data['pis_nis_pasep_number']  = trim(strip_tags($_POST['pis_nis_pasep_number']));
$data['pis_nis_pasep_serie']  = trim(strip_tags($_POST['pis_nis_pasep_serie']));
$data['pis_nis_pasep_uf']  = trim(strip_tags($_POST['pis_nis_pasep_uf']));
$data['registration_number']  = trim(strip_tags($_POST['registration_number']));
$data['admission_date']  = sgc_date_format($_POST['admission_date'],'Y-m-d');
$data['dismissal_date']  = sgc_date_format($_POST['dismissal_date'],'Y-m-d');
$data['address_zipcode']  = trim(strip_tags($_POST['address_zipcode']));
$data['address_street']  = trim(strip_tags($_POST['address_street']));
$data['address_city']  = trim(strip_tags($_POST['address_city']));
$data['address_state']  = trim(strip_tags($_POST['address_state']));
$data['address_district']  = trim(strip_tags($_POST['address_district']));
$data['address_complement']  = trim(strip_tags($_POST['address_complement']));
$data['address_number']  = trim(strip_tags($_POST['address_number']));
$data['contract_regime_id']  = trim(strip_tags($_POST['contract_regime_id']));
$data['base_salary'] = sgc_currency($_POST['base_salary']);
$data['degree_education_id']  = trim(strip_tags($_POST['degree_education_id']));
$data['marital_status_id']  = trim(strip_tags($_POST['marital_status_id']));
$data['number_children']  = trim(strip_tags($_POST['number_children']));
$data['nationality']  = trim(strip_tags($_POST['nationality']));
$data['naturalness']  = trim(strip_tags($_POST['naturalness']));
$data['naturalness_state']  = trim(strip_tags($_POST['naturalness_state']));
$data['dismissal_type']  = trim(strip_tags($_POST['dismissal_type']));
$data['dismissal_reason']  = trim(strip_tags($_POST['dismissal_reason']));





if($id){
$data['modified_date'] = date('Y-m-d H:i:s');
$data['modified_user'] = $user['id'];
}else{
$data['created_date'] = date('Y-m-d H:i:s');
$data['created_user'] = $user['id'];
}
$ret = sgc_save_db('sgc_'.$user['customer_code'].'_contributors', $data, $id);

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


if($row_up['name'] != $data['name']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este colaborador';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Nome</strong></p> <strong>De</strong> '.$row_up['name'].' <br><strong>para</strong> '.$data['name'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_contributors_updates', $data_timeline, 0);
}


if($row_up['status'] != $data['status']){


	if($row_up['status'] == 1){
		$up_from = "Admitido";
	}else if($row_up['status'] == 0){
		$up_from = "Despedido";
	}else{
		$up_from = "Férias";
	}

	if($data['status'] == 1){
		$up_to = "Admitido";
	}else if($data['status'] == 0){
		$up_to = "Despedido";
	}else{
		$up_to = "Férias";
	}
	
	
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este colaborador';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Status</strong></p> <strong>De</strong> '.$up_from.' <br><strong>para</strong> '.$up_to;
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_contributors_updates', $data_timeline, 0);
}

if($row_up['rg'] != $data['rg']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este colaborador';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>RG</strong></p> <strong>De</strong> '.$row_up['rg'].' <br><strong>para</strong> '.$data['rg'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_contributors_updates', $data_timeline, 0);
}

if($row_up['cpf'] != $data['cpf']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este colaborador';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>CPF</strong></p> <strong>De</strong> '.$row_up['cpf'].' <br><strong>para</strong> '.$data['cpf'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_contributors_updates', $data_timeline, 0);
}


if($row_up['genre'] != $data['genre']){
  $up_from = ($row_up['genre'] == 'M')?'Masculino':'Feminino';
  $up_to = ($data['genre'] == 'M')?'Masculino':'Feminino';
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este colaborador';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Gênero</strong></p> <strong>De</strong> '.$up_from.' <br><strong>para</strong> '.$up_to;
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_contributors_updates', $data_timeline, 0);
}

if($row_up['birth_date'] != $data['birth_date']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este colaborador';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Nascimento</strong></p> <strong>De</strong> '.sgc_date_format($row_up['birth_date'],'d/m/Y').' <br><strong>para</strong> '.sgc_date_format($data['birth_date'],'d/m/Y');
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_contributors_updates', $data_timeline, 0);
}

if($row_up['email'] != $data['email']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este colaborador';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>E-mail</strong></p> <strong>De</strong> '.$row_up['email'].' <br><strong>para</strong> '.$data['email'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_contributors_updates', $data_timeline, 0);
}

if($row_up['phone'] != $data['phone']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este colaborador';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Tel. Residencial</strong></p> <strong>De</strong> '.$row_up['phone'].' <br><strong>para</strong> '.$data['phone'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_contributors_updates', $data_timeline, 0);
}

if($row_up['cellphone'] != $data['cellphone']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este colaborador';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Tel. Principal</strong></p> <strong>De</strong> '.$row_up['cellphone'].' <br><strong>para</strong> '.$data['cellphone'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_contributors_updates', $data_timeline, 0);
}

if($row_up['cellphone_secondary'] != $data['cellphone_secondary']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este colaborador';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Tel. Secundario</strong></p> <strong>De</strong> '.$row_up['cellphone_secondary'].' <br><strong>para</strong> '.$data['cellphone_secondary'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_contributors_updates', $data_timeline, 0);
}

if($row_up['address_zipcode'] != $data['address_zipcode']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este colaborador';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>CEP</strong></p> <strong>De</strong> '.$row_up['address_zipcode'].' <br><strong>para</strong> '.$data['address_zipcode'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_contributors_updates', $data_timeline, 0);
}


if($row_up['address_street'] != $data['address_street']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este colaborador';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Rua</strong></p> <strong>De</strong> '.$row_up['address_street'].' <br><strong>para</strong> '.$data['address_street'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_contributors_updates', $data_timeline, 0);
}

if($row_up['address_city'] != $data['address_city']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este colaborador';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Cidade</strong></p> <strong>De</strong> '.$row_up['address_city'].' <br><strong>para</strong> '.$data['address_city'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_contributors_updates', $data_timeline, 0);
}


if($row_up['address_state'] != $data['address_state']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este colaborador';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Estado</strong></p> <strong>De</strong> '.$row_up['address_state'].' <br><strong>para</strong> '.$data['address_state'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_contributors_updates', $data_timeline, 0);
}

if($row_up['address_district'] != $data['address_district']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este colaborador';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Bairro</strong></p> <strong>De</strong> '.$row_up['address_district'].' <br><strong>para</strong> '.$data['address_district'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_contributors_updates', $data_timeline, 0);
}

if($row_up['address_number'] != $data['address_number']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este colaborador';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Número</strong></p> <strong>De</strong> '.$row_up['address_number'].' <br><strong>para</strong> '.$data['address_number'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_contributors_updates', $data_timeline, 0);
}

if($row_up['address_complement'] != $data['address_complement']){
$data_timeline = array();
$data_timeline['created_user'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['item_id'] = $id;
$data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
$data_timeline['title'] = 'Atualizou este colaborador';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Complemento</strong></p> <strong>De</strong> '.$row_up['address_complement'].' <br><strong>para</strong> '.$data['address_complement'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_contributors_updates', $data_timeline, 0);
}

}//END IF ID


    
}break;

case 'save_leave':
{
	
	$data = array();
	$data['reference'] = trim($_POST['reference']);
	$data['date_start'] = sgc_date_format($_POST['date_start'],'Y-m-d');
	$data['date_end'] = sgc_date_format($_POST['date_end'],'Y-m-d');
	$data['observations'] = trim($_POST['observations']);
	$data['contributir_id'] = trim($_POST['id']);
	$data['created_user'] = $user['id'];
    $data['created_date'] = date('Y-m-d H:i:s');
    $ret = sgc_save_db('sgc_'.$user['customer_code'].'_contributors_leave',$data, 0);

    if (!$ret['error_number']) {
    	echo "<script>new PNotify({title: 'Sucesso!',text: 'Dados salvos com sucesso.',type: 'success'});</script>";	
    	echo "<script>$('#control_cad').removeClass('active'); $('#control_lea').addClass('active'); $('#leave').addClass('active'); $('#cadastro').removeClass('active');</script>";
    	
    }else{
    	echo "<script>new PNotify({title: 'Error!',text: 'Não foi possível realizar esta operação.',type: 'error'});</script>";
    }




}break;
	
	
}
}


$query = "SELECT a.*,u.name AS name_created,um.name AS name_modified FROM sgc_".$user['customer_code']."_contributors AS a LEFT JOIN users AS u ON u.id = a.created_user LEFT JOIN users AS um ON um.id = a.modified_user WHERE a.id = :id";
$search = conecta()->prepare($query);
$search->bindValue(':id', $_POST['id']);
$search->execute();
$row = $search->fetch(PDO::FETCH_ASSOC);


//timeline
$query = "SELECT a.*,u.name AS name_user FROM sgc_".$user['customer_code']."_contributors_updates AS a LEFT JOIN users AS u ON u.id = a.created_user WHERE a.item_id = :id ORDER BY a.id DESC";
$search = conecta()->prepare($query);
$search->bindValue(':id', $_POST['id']);
$search->execute();
$rows_timeline = $search->fetchAll(PDO::FETCH_ASSOC);

//Popula Categorias
$query = "SELECT id,name FROM sgc_".$user['customer_code']."_contributors_categories WHERE status = 1 ORDER BY name";
$search = conecta()->prepare($query);
$search->execute();
$rows_categories = $search->fetchAll(PDO::FETCH_ASSOC);

//Popula regime contrato
$query = "SELECT * FROM contract_regime";
$search = conecta()->prepare($query);
$search->execute();
$rows_contract_regime = $search->fetchAll(PDO::FETCH_ASSOC);


//Popula grau de instroção
$query = "SELECT * FROM degree_of_education";
$search = conecta()->prepare($query);
$search->execute();
$rows_degree_education = $search->fetchAll(PDO::FETCH_ASSOC);

//Popula estado civil
$query = "SELECT * FROM marital_status";
$search = conecta()->prepare($query);
$search->execute();
$rows_marital_status = $search->fetchAll(PDO::FETCH_ASSOC);



//Popula férias
$query = "SELECT * FROM sgc_".$user['customer_code']."_contributors_leave WHERE contributir_id = :id";
$search = conecta()->prepare($query);
$search->bindValue(':id', $_POST['id']);
$search->execute();
$rows_leave = $search->fetchAll(PDO::FETCH_ASSOC);






?>
<section class="content-header">
      <h1>Colaborador</h1>
      <ol class="breadcrumb">
        <li><a href="javascript:void();" onclick="open_target('target=home');"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void();" onclick="open_target('target=contributors');">Gestão de colaboradores</a></li>
        <li class="active">Colaborador</li>
      </ol>
    </section>

<!-- Main content -->
<section class="content">
<div class="row">


        <!-- /.col -->
        <div class="col-md-8">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">              
              <li class="active" id="control_cad"><a href="#cadastro" data-toggle="tab">Cadastro</a></li>
              <?php if($_POST['id']): ?>
              <li id="control_lea"><a href="#leave" data-toggle="tab">Registros de férias</a></li>              
              <li id="control_atu"><a href="#timeline" data-toggle="tab">Atualizações</a></li>   
              <?php endif; ?>           
            </ul>
      <div class="tab-content">


<div class="tab-pane active" id="cadastro">
                <form role="form" action="" method="POST" name="form_main" enctype="multipart/form-data">
    <div class="box-body">


<div class="panel box box-primary">
  <div class="box-header with-border">
    <h4 class="box-title">
      <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="false" class="collapsed">
        Dados básicos
      </a>
    </h4>
  </div>

  <div id="collapse1" class="panel-collapse collapse col_open" aria-expanded="true" style="height: 0px;">
    <div class="box-body">
      

      <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text"  name="name" autocomplete="off"  class="form-control" id="name" placeholder="Nome completo" value="<?php echo $row['name']?>">
            </div>
        </div>
        
        </div><!--END ROW-->


   <div class="row">
     <div class="col-sm-2">
            <div class="form-group">
                <label for="genre">Gênero</label>
                <select name="genre" id="genre" class="form-control">
                    <option value="M" <?php echo ($row['genre'] == 'M')?'selected="selected"':'';?>>M</option>
                    <option value="F" <?php echo ($row['genre'] == 'F')?'selected="selected"':'';?>>F</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4">
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
                <label for="degree_education_id">Escolaridade</label>
				<select name="degree_education_id" id="degree_education_id" class="form-control">
					<option value="">--Selecione--</option>
					<?php foreach ($rows_degree_education as $row_degree_education): ?>
					<option value="<?php echo $row_degree_education['id']?>" <?php echo ($row['degree_education_id'] == $row_degree_education['id'])?'selected':''; ?>><?php echo $row_degree_education['name']?></option>
					<?php endforeach; ?>
				</select>
        </div>
        </div>       

   </div><!--END ROW-->


   <div class="row">


   	<div class="col-sm-3">
            <div class="form-group">
                <label for="marital_status_id">Estado civil</label>
				<select name="marital_status_id" id="marital_status_id" class="form-control">
					<option value="">--Selecione--</option>
					<?php foreach ($rows_marital_status as $row_marital_status): ?>
					<option value="<?php echo $row_marital_status['id']?>" <?php echo ($row['marital_status_id'] == $row_marital_status['id'])?'selected':''; ?>><?php echo $row_marital_status['name']?></option>
					<?php endforeach; ?>
				</select>
        </div>
        </div>

        
        <div class="col-sm-3">
            <div class="form-group">
                <label for="number_children">N° de filhos</label>
                <input type="number"  name="number_children" autocomplete="off"  class="form-control" id="number_children" value="<?php echo $row['number_children']?>">
            </div>
        </div>

   <div class="col-sm-6">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="text"  name="email" autocomplete="off"  class="form-control" id="email" placeholder="E-mail" value="<?php echo $row['email']?>">
            </div>
        </div>

         
   </div><!--END ROW-->  


   <div class="row">


        <div class="col-sm-6">
            <div class="form-group">
                <label for="nationality">Nacionalidade</label>
                <input type="text"  name="nationality" autocomplete="off"  class="form-control" id="nationality" value="<?php echo $row['nationality']?>">
            </div>
        </div>

   	<div class="col-sm-4">
            <div class="form-group">
                <label for="naturalness">Naturalidade</label>
                <input type="text"  name="naturalness" autocomplete="off"  class="form-control" id="naturalness" value="<?php echo $row['naturalness']?>">
            </div>
        </div>



       <div class="col-sm-2">
            <div class="form-group">
                <label for="naturalness_state">Estado</label>
                <input type="text"  name="naturalness_state" autocomplete="off"  class="form-control" id="naturalness_state" value="<?php echo $row['naturalness_state']?>">
            </div>
        </div>

         
   </div><!--END ROW-->         

        


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
      
    </div>
  </div>
</div><!--END dados basicos-->

<div class="panel box box-primary">
  <div class="box-header with-border">
    <h4 class="box-title">
      <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="false" class="collapsed">
        Documentos pessoais
      </a>
    </h4>
  </div>
  
  <div id="collapse2" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
    <div class="box-body">
      
      
<div class="row">
          <div class="col-sm-4">
            <div class="form-group">
                <label for="rg">RG</label>
                <input class="form-control" value="<?php echo $row['rg'] ?>" OnKeyPress="return sgc_masc_fild(event, this, '########-##');" type="text" id="rg" name="rg" />
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="rg_expedition_date">Expedição</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
          <input type="text" value="<?php echo sgc_date_format($row['rg_expedition_date'], 'd/m/Y') ;?>" OnKeyPress="return sgc_masc_fild(event, this, '##/##/####');"  name="rg_expedition_date" id="rg_expedition_date" class="form-control pull-right">
                </div>
            </div>
        </div>


        <div class="col-sm-4">
            <div class="form-group">
                <label for="cnh">Emissor</label>
                <input class="form-control" value="<?php echo $row['rg_emitter'] ?>" type="text" id="rg_emitter" name="rg_emitter" />
            </div>
        </div>
</div><!--ROW-->
<div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="cnh">CNH</label>
                <input class="form-control" value="<?php echo $row['cnh'] ?>" OnKeyPress="return sgc_masc_fild(event, this, '########-##');" type="text" id="cnh" name="cnh" />
            </div>
        </div>        

        <div class="col-sm-2">
            <div class="form-group">
                <label for="cnh_category">Categoria</label>
				<select name="cnh_category" id="cnh_category" class="form-control">
					<option value="0" <?php echo ($row['cnh_category'] == '0')?'selected':''?>>--Selecione--</option>
					<option value="A" <?php echo ($row['cnh_category'] == 'A')?'selected':''?>>A</option>
					<option value="B" <?php echo ($row['cnh_category'] == 'B')?'selected':''?>>B</option>
					<option value="AB" <?php echo ($row['cnh_category'] == 'AB')?'selected':''?>>AB</option>
					<option value="C"><?php echo ($row['cnh_category'] == 'C')?'selected':''?>C</option>
					<option value="D" <?php echo ($row['cnh_category'] == 'D')?'selected':''?>>D</option>
					<option value="E" <?php echo ($row['cnh_category'] == 'E')?'selected':''?>>E</option>
				</select>
        </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <label for="first_cnh">1° CNH</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
          <input type="text" value="<?php echo sgc_date_format($row['first_cnh'], 'd/m/Y') ;?>" OnKeyPress="return sgc_masc_fild(event, this, '##/##/####');"  name="first_cnh" id="first_cnh" class="form-control pull-right">
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <label for="cnh_due">Validade</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
          <input type="text" value="<?php echo sgc_date_format($row['cnh_due'], 'd/m/Y') ;?>" OnKeyPress="return sgc_masc_fild(event, this, '##/##/####');"  name="cnh_due" id="cnh_due" class="form-control pull-right">
                </div>
            </div>
        </div>
        
        </div><!--END ROW-->

        <div class="row">
        	<div class="col-sm-4">
            <div class="form-group">
                <label for="cpf">CPF</label>
                <input class="form-control" value="<?php echo $row['cpf'] ?>" OnKeyPress="return sgc_masc_fild(event, this, '###.###.###-##');" type="text" id="cpf" name="cpf" />
            </div>
        </div>
        

        <div class="col-sm-4">           
            <div class="form-group">
                <label for="title_voter">Título de eleitor</label>              
                    <input type="text"  value="<?php echo $row['title_voter'] ?>" class="form-control" id="title_voter" name="title_voter" OnKeyPress="return sgc_masc_fild(event, this, '##.#####.##-#');">
              
            </div>
        </div>

        <div class="col-sm-2">           
            <div class="form-group">
                <label for="title_voter_zone">Zona</label>              
                    <input type="number"  value="<?php echo $row['title_voter_zone'] ?>" class="form-control" id="title_voter_zone" name="title_voter_zone">
              
            </div>
        </div>   

        <div class="col-sm-2">           
            <div class="form-group">
                <label for="title_voter_session">Sessão</label>              
                    <input type="number"  value="<?php echo $row['title_voter_session'] ?>" class="form-control" id="title_voter_session" name="title_voter_session">
              
            </div>
        </div>         


        </div><!--END ROW-->


        <div class="row">
        	<div class="col-sm-4">           
            <div class="form-group">
                <label for="pis_nis_pasep">NIS/PIS/PASEP</label>                
                    <input type="text"  value="<?php echo $row['pis_nis_pasep'] ?>" class="form-control" id="pis_nis_pasep" name="pis_nis_pasep" OnKeyPress="return sgc_masc_fild(event, this, '##.#####.##-#');">             
            </div>
        </div>

        <div class="col-sm-4">           
            <div class="form-group">
                <label for="pis_nis_pasep_number">Número</label>                
                    <input type="text"  value="<?php echo $row['pis_nis_pasep_number'] ?>" class="form-control" id="pis_nis_pasep_number" name="pis_nis_pasep_number">             
            </div>
        </div>

        <div class="col-sm-2">           
            <div class="form-group">
                <label for="pis_nis_pasep_serie">Série</label>                
                    <input type="text"  value="<?php echo $row['pis_nis_pasep_serie'] ?>" class="form-control" id="pis_nis_pasep_serie" name="pis_nis_pasep_serie">             
            </div>
        </div>

        <div class="col-sm-2">           
            <div class="form-group">
                <label for="pis_nis_pasep_uf">UF</label>                
                    <input type="text"  value="<?php echo $row['pis_nis_pasep_uf'] ?>" class="form-control" id="pis_nis_pasep_uf" name="pis_nis_pasep_uf">             
            </div>
        </div>

        </div><!--END ROW-->


    </div>
  </div>
</div><!--END dados documentos-->


<div class="panel box box-primary">
  <div class="box-header with-border">
    <h4 class="box-title">
      <a data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="false" class="collapsed">
        Dados residênciais
      </a>
    </h4>
  </div>
  
  <div id="collapse3" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
    <div class="box-body">
      
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
      
    </div>
  </div>
</div><!--END dados documentos-->


<div class="panel box box-primary">
  <div class="box-header with-border">
    <h4 class="box-title">
      <a data-toggle="collapse" data-parent="#accordion" href="#collapse4" aria-expanded="false" class="collapsed">
        Dados internos
      </a>
    </h4>
  </div>
  
  <div id="collapse4" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
    <div class="box-body">

<div class="row">
	<div class="col-sm-3">
            <div class="form-group">
                <label for="registration_number">Matricula</label>
                <input class="form-control" value="<?php echo $row['registration_number'] ?>"  type="text" id="registration_number" name="registration_number" />
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <label for="admission_date">Admissão</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                       <input type="text" value="<?php echo sgc_date_format(($row['admission_date'])?$row['admission_date']:date('Y-m-d'), 'd/m/Y') ;?>" OnKeyPress="return sgc_masc_fild(event, this, '##/##/####');"  name="admission_date" id="admission_date" class="form-control pull-right">
                </div>
            </div>
        </div>        


        <div class="col-sm-3">
            <div class="form-group">
                <label for="contract_regime_id">Regime de contrato</label>
				<select name="contract_regime_id" id="contract_regime_id" class="form-control">
					<?php foreach ($rows_contract_regime as $row_contract_regime): ?>
					<option value="<?php echo $row_contract_regime['id']?>" <?php echo ($row['contract_regime_id'] == $row_contract_regime['id'])?'selected':''; ?>><?php echo $row_contract_regime['name']?></option>
					<?php endforeach; ?>
				</select>
        </div>
        </div>



        <div class="col-sm-3">            
            <div class="form-group">
                <label for="base_salary">Salário base</label>
<input type="text" class="form-control currency" id="base_salary" name="base_salary" value="<?php echo number_format($row['base_salary'],2,',','.');?>">
            </div>
          </div>
        


</div><!--END ROW-->

<div class="row">
	


<div class="col-sm-5">
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" onchange="type_dismissal($(this));" id="status" class="form-control">
                     <option value="">--Selecione--</option>
                    <option <?php echo ($row['status'] == '1')?'selected':''; ?> value="1">Admitido</option>                    
                    <option <?php echo ($row['status'] == '0')?'selected':''; ?> value="0">Despedido</option>
                </select>
            </div>
        </div>

 
<!--<div class="data_dismissal">-->
          <div class="col-sm-3 data_dismissal">
            <div class="form-group">
                <label for="dismissal_date">Demissão</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                       <input type="text" value="<?php echo sgc_date_format(($row['dismissal_date'])?$row['dismissal_date']:date('Y-m-d'), 'd/m/Y') ;?>" OnKeyPress="return sgc_masc_fild(event, this, '##/##/####');"  name="dismissal_date" id="dismissal_date" class="form-control pull-right">
                </div>
            </div>
        </div>

        <div class="col-sm-4 data_dismissal">
            <div class="form-group">
                <label for="dismissal_type">Tipo</label>
				<select name="dismissal_type" id="dismissal_type" class="form-control">
					<option value="0" <?php echo ($row['dismissal_type'] == '')?'selected':''?>>--Selecione--</option>
					<option value="1" <?php echo ($row['dismissal_type'] == '1')?'selected':''?>>Com justa causa</option>
					<option value="2" <?php echo ($row['dismissal_type'] == '2')?'selected':''?>>Sem justa causa</option>
					
				</select>
        </div>
        </div>

</div><!--END ROW-->

<div class="row">
	<div class="col-sm-12 data_dismissal">            
            <div class="form-group">
                <label for="dismissal_reason">Motivo da demissão</label>
                <textarea name="dismissal_reason" class="form-control" id="dismissal_reason" cols="30" rows="5"><?php echo $row['dismissal_reason']; ?></textarea>

            </div>
          </div>
</div><!--END ROW-->
<!--</div>END DATA DEMISSIONAL-->


    </div>
  </div>
</div><!--END dados internos-->

   

<div class="row">
  <div class="col-sm-6">
    <input type="hidden" name="id" id="id" value="<?php echo $_POST['id'];?>">
    <button type="button" name="sgc_save" id="sgc_save" onclick="sgc_save_main($(this));" class="btn btn-primary btn_action"><i class="fa fa-check"></i> Salvar</button>
  </div>
  <div class="col-sm-6">
    <button type="button" onclick="open_target('target=contributors', $(this));" class="btn btn-default pull-right rr"> Voltar</button>
  </div>
    
</div>    
</div><!--END box body-->              
              
</form>

  </div><!--END PANE CADASTRO -->




<div class="tab-pane" id="leave">                
    <div class="box-body">


<div class="panel box box-primary">
  <div class="box-header with-border">
    <h4 class="box-title">
      <a data-toggle="collapse" data-parent="#accordion" href="#collapse5" aria-expanded="false" class="collapsed">
        Cadastrar férias
      </a>
    </h4>
  </div>

  <div id="collapse5" class="panel-collapse collapse col_open2" aria-expanded="true" style="height: 0px;">
    <div class="box-body">
<form name="form_leave" id="form_leave">
    	<div class="row">
        <div class="col-sm-2">
            <div class="form-group">
                <label for="reference">Referência</label>
                <input type="text"  name="reference" autocomplete="off"  class="form-control" id="reference" placeholder="Ex <?php echo date('Y');?>" value="">
            </div>
        </div>

<div class="col-sm-5">
        <div class="form-group">
                <label>Início</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  
                  <input type="text" class="form-control" id="date_start" name="date_start" value="" />
                </div>                
              </div>
        </div><!-- /.input group -->



<div class="col-sm-5">
        <div class="form-group">
                <label>Término</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>                  
                  <input type="text" id="date_end" name="date_end" class="form-control" value="" />
                </div>                
              </div>
        </div><!-- /.input group -->


        
        </div><!--END ROW-->

        <div class="row">
        	<div class="col-sm-12">
        		<label for="observations">Observações</label>
        		<textarea name="observations" id="observations" class="form-control"></textarea>
        	</div>
        </div><!--END ROW-->

 
<div class="row" style="margin-top: 15px;">
  <div class="col-sm-6">
    <input type="hidden" name="id" id="id" value="<?php echo $_POST['id'];?>">
    <button type="button" name="save_leave" id="save_leave" onclick="sgc_save_leave($(this));" class="btn btn-primary btn_action"><i class="fa fa-check"></i> Salvar</button>
  </div>

  <div class="col-sm-6">
    <button type="button" onclick="open_target('target=contributors', $(this));" class="btn btn-default pull-right rr"> Voltar</button>
  </div>
  </form>
</div><!--END ROW-->


</div><!--END BOX-->





</div>
</div><!--END cadastrar férias-->






<div class="panel box box-primary">
  <div class="box-header with-border">
    <h4 class="box-title">
      <a data-toggle="collapse" data-parent="#accordion" href="#collapse6" aria-expanded="false" class="collapsed">
        Histórico de férias
      </a>
    </h4>
  </div>

  <div id="collapse6" class="panel-collapse collapse col_open" aria-expanded="true" style="height: 0px;">
    <div class="box-body">
	
	
		<table id="contributors_leave" class="table table-bordered table-hover">

                <thead>
                <tr>                  
                  <th style="width: 50px;" >ID</th>
                  <th>Observações</th>
                  <th>Referência</th>
                  <th>Início</th>
                  <th>Término</th>  
                </tr>
                </thead>
                <tbody>
<?php foreach ($rows_leave as $row_leave): ?>
<tr>                  
                  <th style="width: 50px;" ><?php echo $row_leave['id']?></th>
                  <th><?php echo $row_leave['observations']?></th>
                  <th><?php echo $row_leave['reference']?></th>
                  <th><?php echo sgc_date_format($row_leave['date_start'],'d/m/Y');?></th>
                  <th><?php echo sgc_date_format($row_leave['date_end'],'d/m/Y');?></th>  
</tr>

<?php endforeach; ?>
                </tbody>
                
      </table>

</div>
</div>     
</div><!--END PANE ferias-->




    </div><!--END BOX-->   
</div><!--END PANES-->





              
              <div class="tab-pane" id="timeline">
                <!-- The timeline -->
                <ul class="timeline timeline-inverse">

<?php if(count($rows_timeline) == 0): ?>

	<li>
	 <i class="fa fa-clock-o bg-gray"></i>

	 <div class="timeline-item">
<span class="time"><i class="fa fa-clock-o"></i> <?php echo date('d/m/Y H:i:s') ?></span>

<h3 class="timeline-header"><span style="color: #3c8dbc";>Sistema</span> Este colaborador não possui atualizações</h3>

                      
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


$('#contributors_leave').DataTable({
      'paging'      : false,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });



$('#birth_date').datepicker({
autoclose: true,
language: 'br'
});

$('#cnh_due').datepicker({
autoclose: true,
language: 'br'
});

$('#rg_expedition_date').datepicker({
autoclose: true,
language: 'br'
});

$('#first_cnh').datepicker({
autoclose: true,
language: 'br'
});

$('#admission_date').datepicker({
autoclose: true,
language: 'br'
});

$('#dismissal_date').datepicker({
autoclose: true,
language: 'br'
});


$('#date_start').datepicker({
autoclose: true,
language: 'br'
});

$('#date_end').datepicker({
autoclose: true,
language: 'br'
});


$('#reservation').daterangepicker({
	"opens": "right",
    "showDropdowns": true,
    locale: {
      format: 'DD/MM/YYYY'
  },
    "startDate": "01/01/<?php echo date('Y');?>",
    "endDate": <?php echo date('d/m/Y')?>
}, function(start, end, label) {
  
  $('#date_start').val(start.format('YYYY-MM-DD'));
  $('#date_end').val(end.format('YYYY-MM-DD'));
});



$('.col_open').collapse('show');




if($('#status').val() == 0){
    $('.data_dismissal').slideDown('slow');
  }else{
    $('.data_dismissal').slideUp('slow');
   
  }


});



function sgc_save_main(btn){

var form    		= $('form[name="form_main"]');
var name    		= $('#name');
var status   	  	= $('#status');
var description   	= $('#description');
var params 			= 'target=contributor&exec=save_contributor&'+form.serialize();

if(valid_fild('required',name) && valid_fild('select',status)){
open_target(params,btn);
}
}

function sgc_save_leave(btn){

var form    		= $('form[name="form_leave"]');
var reference    	= $('#reference');
var date_start   	= $('#date_start');
var date_end   	  	= $('#date_end');
var observations   	= $('#observations');
var params 			= 'target=contributor&exec=save_leave&'+form.serialize();

if(valid_fild('required',reference) && valid_fild('required',date_start) && valid_fild('required',date_end) && valid_fild('required',observations)){
open_target(params,btn);
}
}


function type_dismissal(obj){

  var type = obj.val();

  if(type == 0){
    $('.data_dismissal').slideDown('slow');
  }else{
    $('.data_dismissal').slideUp('slow');
   
  }


}


  </script>


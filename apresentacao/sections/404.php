<?php require_once "sgc_functions.php";

$data = array();
$data['mod_name'] 	= "not_found";
$data['user_id'] 	= $user['id'];
$data['date_lock']		= date('Y-m-d H:i:s');
$ret = sgc_save_db('sgc_'.$user['customer_code'].'_access_locked', $data, 0);

?>
<section class="content container-fluid">
<div class="col-sm-offset-3 col-sm-6">
   <div class="box box-danger">
     <div class="box-body">
        <h3 class="text-center text-danger">Erro 404!</h3>
        <p class="text-center">Você tentou acessar esta página de maneira indevida e está sendo monitorado.</p>
			<hr class="text-danger">
			<p><strong>Nome:</strong> <?php echo $user['name']?></p>
			<p><strong>Data:</strong> <?php echo date('d/m/Y H:i:s')?></p>
			<p><strong>Data:</strong> <?php echo date('d/m/Y H:i:s')?></p>      
  </div>
  </div>
</div>
</section>


<?php

if(!empty($_POST['exec'])){

switch ($_POST['exec']) {

case 'save_profile':
{
$query = "SELECT * FROM users WHERE id = :id";
$search = conecta()->prepare($query);
$search->bindValue(':id', $user['id']);
$search->execute();
$row_up = $search->fetch(PDO::FETCH_ASSOC);


$id = $user['id'];
$data = array();

if($_POST['password2'] != '' && $_POST['password'] != ''){  
$data['password_show']  = $_POST['password2'];
$data['password']       = trim(md5($_POST['password']));
}

$data['name'] = trim(strip_tags($_POST['name']));
$data['genre'] = trim(strip_tags($_POST['genre']));
$data['birth_date'] = sgc_date_format($_POST['birth_date'],'Y-m-d');
//$data['status'] = trim(strip_tags($_POST['status']));
$data['email'] = trim(strip_tags($_POST['email']));
$data['username'] = trim(strip_tags($_POST['username']));
$data['office'] = trim(strip_tags($_POST['office']));
$data['phone']  = trim(strip_tags($_POST['phone']));
$data['cellphone']  = trim(strip_tags($_POST['cellphone']));
$data['cellphone_secondary']  = trim(strip_tags($_POST['cellphone_secondary']));
$data['address_zipcode']  = trim(strip_tags($_POST['address_zipcode']));
$data['address_street']  = trim(strip_tags($_POST['address_street']));
$data['address_city']  = trim(strip_tags($_POST['address_city']));
$data['address_state']  = trim(strip_tags($_POST['address_state']));
$data['address_district']  = trim(strip_tags($_POST['address_district']));
$data['address_complement']  = trim(strip_tags($_POST['address_complement']));
$data['address_number']  = trim(strip_tags($_POST['address_number']));
$data['modified_date'] = date('Y-m-d H:i:s');
$data['modified_user'] = $user['id'];

$ret = sgc_save_db('users', $data, $id);

if(!$ret['error_number']){
  
  echo "<script>new PNotify({title: 'Sucesso!',text: 'Perfil salvo com sucesso.',type: 'success'});</script>";


  //atualização da linha do tempo
if($row_up['name'] != $data['name']){
$data_timeline = array();
$data_timeline['user_id'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['icon'] = '<i class="fa fa-user bg-aqua"></i>';
$data_timeline['title'] = 'Atualizou o cadastro';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Nome</strong></p> <strong>De</strong> '.$row_up['name'].' <br><strong>para</strong> '.$data['name'];
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_users_timeline', $data_timeline, 0);
}



//atualização da linha do tempo
if($row_up['genre'] != $data['genre']){

$genre_from = ($row_up['genre'] == 'M')?'Mascumino':'Feminino';
$genre_to = ($data['genre'] == 'M')?'Mascumino':'Feminino';

$data_timeline = array();
$data_timeline['user_id'] = $user['id'];
$data_timeline['created_date'] = date('Y-m-d H:i:s');
$data_timeline['icon'] = '<i class="fa fa-user bg-aqua"></i>';
$data_timeline['title'] = 'Atualizou o cadastro';
$data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Gênero</strong></p> <strong>De</strong> '.$genre_from.' <br><strong>para</strong> '.$genre_to;
$new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_users_timeline', $data_timeline, 0);
}

}else{
  print_r($ret);
  echo "<script>new PNotify({title: 'Error!',text: 'Não foi possível realizar esta operação INTERNO.',type: 'error'});</script>";
}



}break;//END save profile


case 'remove_img_profile':  
  {

        $type       = "imagem";
        $mode_name      = "img_profile_user";
        $ret = remove_all_midias($mode_name,$type);

if(!$ret['error_number']){
  
    echo "<script>new PNotify({title: 'Sucesso!',text: 'Imagem removida com sucesso.',type: 'success'});</script>";

    $data_timeline = array();
    $data_timeline['user_id'] = $user['id'];
    $data_timeline['created_date'] = date('Y-m-d H:i:s');
    $data_timeline['icon'] = '<i class="fa fa-camera bg-aqua"></i>';
    $data_timeline['title'] = 'Atualizou o cadastro';
    $data_timeline['content'] = '<p>Removeu a imagem de perfil</p>';
    $new_timeline = sgc_save_db('sgc_'.$user['customer_code'].'_users_timeline', $data_timeline, 0);

    }else{
    print_r($ret);
    echo "<script>new PNotify({title: 'Error!',text: 'Não foi possível realizar esta operação INTERNO.',type: 'error'});</script>";
    }      

        


  }break;


}//END SWITCH
}//END IF
















$query = "SELECT u.*,m.name AS name_img_profile,m.type  FROM users AS u LEFT JOIN sgc_".$user['customer_code']."_medias AS m ON m.user_id = u.id AND m.mode_name = 'img_profile_user' WHERE u.id = :id ORDER BY m.id DESC";
$search = conecta()->prepare($query);
$search->bindValue(':id', $user['id']);
$search->execute();
$row = $search->fetch(PDO::FETCH_ASSOC);
if($row['name_img_profile']){
  $img_profile_src =  "uploads/".$row['type']."/".$row['name_img_profile'];
}else{
  $img_profile_src = 'img/user.png';
}

//consulta linha do tempo
$query = "SELECT tl.*,u.name AS name_user FROM sgc_".$user['customer_code']."_users_timeline AS tl LEFT JOIN users AS u ON u.id = tl.user_id ORDER BY tl.created_date DESC";
$search = conecta()->prepare($query);
$search->execute();
$rows_timeline = $search->fetchAll(PDO::FETCH_ASSOC);


?>

<section class="content-header">
      <h1>Perfil do usuário</h1>
      <ol class="breadcrumb">
        <li><a href="javascript:void();" onclick="open_target('target=home');"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Perfil do usuário</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
<div class="profile-user-img img-responsive img-circle" style="background-image: url(<?php echo $img_profile_src;?>);width: 150px;
    height: 150px; background-position: center;
    background-size: 150px; background-repeat: no-repeat; border-radius: 50% !important;">

</div>

              <h3 class="profile-username text-center"><?php echo $row['name'];?></h3>

              <p class="text-muted text-center"><?php echo $row['office'];?></p>

              <div class="row">
                <div class="col-sm-12">
        <form name="img_profile">
                <div class="input-group input-group-sm">
                <input type="file" name="arquivo" id="arquivo" class="form-control">
                <?php if($row['name_img_profile']){ ?>
                    <span class="input-group-btn">
                <button type="button" id="send_img" title="Remover imagem de perfil" onclick ="trash_img_profile();" class="btn btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                    </span>
                    <?php } ?>
              </div>       
 
</form>
              </div>
            </div><!--END ROW-->


        <div class="row" style="margin-top: 10px;">
          <div class="col-sm-12">
            <div class="progress mb-2" id="profile_b" style="display: none;">
  <div class="progress-bar progress-bar-striped progress-bar-animated" id="profile_p" role="progressbar" style="width: 0%; height: 20px;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
</div>
          </div>
        </div>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Followers</b> <a class="pull-right">1,322</a>
                </li>
                <li class="list-group-item">
                  <b>Following</b> <a class="pull-right">543</a>
                </li>
                <li class="list-group-item">
                  <b>Friends</b> <a class="pull-right">13,287</a>
                </li>
              </ul>

              <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">              
              <li class="active"><a href="#cadastro" data-toggle="tab">Cadastro</a></li>
              <li><a href="#timeline" data-toggle="tab">Timeline</a></li>              
            </ul>
            <div class="tab-content">




<div class="tab-pane active" id="cadastro">
                <form role="form" action="" method="POST" name="profile_user" enctype="multipart/form-data">
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
                <label for="genre">Gênero</label>
                <select name="genre" id="genre" class="form-control">
                     <option value="">--Selecione--</option>
                    <option <?php echo ($row['genre'] == 'M')?'selected':''; ?> value="M">M</option>
                    <option <?php echo ($row['genre'] == 'F')?'selected':''; ?> value="F">F</option>
                </select>
            </div>
        </div>
        </div><!--END ROW-->

        <div class="row">

          <div class="col-sm-3">
            <div class="form-group">
                <label for="birth_date">Data de Nascimento</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
          <input type="text" value="<?php echo sgc_date_format($row['birth_date'], 'd/m/Y') ;?>" OnKeyPress="return sgc_masc_fild(event, this, '##/##/####');"  name="birth_date" id="birth_date" class="form-control pull-right">
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="text"  name="email" autocomplete="off"  class="form-control" id="email" placeholder="E-mail" value="<?php echo $row['email']?>">
            </div>
        </div>

        <div class="col-sm-5">
            <div class="form-group">
                <label for="office">Função</label>
                <input type="text"  name="office" autocomplete="off"  class="form-control" id="office" placeholder="Função ou cargo na empresa" value="<?php echo $row['office']?>">
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
      <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="username">Usuário</label>
                <input type="text"  name="username" autocomplete="off"  class="form-control" id="username" placeholder="Nome completo" value="<?php echo $row['username']?>">
            </div>
        </div>
        
        <div class="col-sm-4">
            <div class="form-group">
                <label for="password">Nova senha</label>
                <input type="password"  name="password" value="" autocomplete="off"  class="form-control" id="password">
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="password2">Confirmar senha</label>
                <input type="password"  name="password2" value="" autocomplete="off"  class="form-control" id="password2">
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
                  <button type="button" name="sgc_save" id="sgc_save" onclick="sgc_save_main();" class="btn btn-primary"><i class="fa fa-check"></i> Salvar</button>
                  </div>
              </div>
                  
</div><!--END box body-->
              
             
            </form>
</div><!--END PANE CADASTRO -->






              
              <div class="tab-pane" id="timeline">
                <!-- The timeline -->
                <ul class="timeline timeline-inverse">

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
                  
                  <li>
                    <i class="fa fa-clock-o bg-gray"></i>
                  </li>
                </ul>
              </div>
              <!-- END PANE TIMELINE -->

              





            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
<script>

function sgc_save_main(){
var form    = $('form[name="profile_user"]');
var btn     = $('#sgc_save');
var name    = $('#name');
var genre   = $('#genre');
var password = $('#password');

var params      = 'target=profile&exec=save_profile&'+form.serialize();
if(valid_fild('required',name) && valid_fild('select',genre) && valid_fild('password',password) ){
open_target(params,btn);

//send_form(form,btn,'save_profile',false,'profile');
}

}


function trash_img_profile(){
             
  var params    = 'target=profile&exec=remove_img_profile';
  var btn       = $('#send_img');
  //var target    = 'profile';
  //sgc_admin(params,target);
  open_target(params,btn);

            
}


$('input[type=file]').on("change", function(){  
$(this).each(function(index){
        if ($('input[type=file]').eq(index).val() != ""){

            var sender    = $('form[name="img_profile"]'); 
            var rotina    = 'save_img_profile';
            send_form_file(sender,'profile',rotina);
            
            
            
        }
    });

});


$('#birth_date').datepicker({
autoclose: true,
language: 'br'
});
</script>


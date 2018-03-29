<?php


if(!empty($_POST['exec'])){

switch ($_POST['exec']) {

case 'delete_itens_selected':
{

$ids = strip_tags(trim($_POST['ids_delete']));
$tabela = "sgc_".$user['customer_code']."_contributors";
$ret = sgc_delete_db($tabela, $ids);
if(!$ret[2]){

	echo "<script>new PNotify({title: 'Sucesso!',text: 'Itens excluidos com sucesso.',type: 'success'});</script>";
}else{
	echo "<script>new PNotify({title: 'Error!',text: 'Não foi possível excluir este item.',type: 'error'});</script>";
}

}break;

}
}

$query = "SELECT * FROM sgc_".$user['customer_code']."_contributors";
$search = conecta()->prepare($query);
$search->execute();
$rows = $search->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="content-header">
      <h1>Gestão de colaboradores</h1>
      <ol class="breadcrumb">
        <li><a href="javascript:void();" onclick="open_target('target=home');"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Gestão de colaboradores</li>
      </ol>
    </section>

<!-- Main content -->
<section class="content">
          <div class="box box-primary">
            <div class="box-header">


            </div>
            <!-- /.box-header -->
            <div class="box-body">
            	<table id="contributors" class="table table-bordered table-hover">

                <thead>
                <tr>
                  <th style="width: 30px;" id="check_all_r"><input type="checkbox" onclick="select_all_item_list();" class="check_all"></th>
                  <th style="width: 50px;" >ID</th>
                  <th style="width: 100px;">Nome</th>
                  <th>E-mail</th>
                  <th>Tel. Principal</th>
                  <th>Cidade</th>
                  <th style="width: 50px;">Estado</th>
                  <th style="width: 50px;">Status</th>                  
                </tr>
                </thead>
                <tbody>

<?php foreach($rows as $row):
$status = ($row['status'] ==1)?'':'';

if($row['status'] == 1){
    $status = '<span class="label label-success">Admitido</span>';
  }else if($row['status'] == 0){
    $status = '<span class="label label-danger">Despedido</span>';
  }else{
    $status = '<span class="label label-info">Férias</span>';
  }


?>
    <tr>
      <td><input type="checkbox" class="check_item" value="<?php echo $row['id']?>"></td>
      <td><?php echo $row['id']?></td>
      <td onclick="open_target('target=contributor&id=<?php echo $row['id'];?>');" style="cursor: pointer;"><?php echo $row['name']?></td>
      <td onclick="open_target('target=contributor&id=<?php echo $row['id'];?>');" style="cursor: pointer;"><?php echo $row['email']?></td>
      <td onclick="open_target('target=contributor&id=<?php echo $row['id'];?>');" style="cursor: pointer;"><?php echo $row['cellphone']?></td>
      <td onclick="open_target('target=contributor&id=<?php echo $row['id'];?>');" style="cursor: pointer;"><?php echo $row['address_city']?></td>
      <td onclick="open_target('target=contributor&id=<?php echo $row['id'];?>');" style="cursor: pointer;"><?php echo $row['address_state']?></td>
      <td><?php echo $status;?></td>
    </tr>
    <?php endforeach; ?>
                
                </tbody>
                <tfoot>
                <tr>
                  <th style="width: 30px;" id="id"><input type="checkbox" class="check_all" value="<?php echo $row['id']?>"></th>
                  <th style="width: 50px;">ID</th>
                  <th>Nome</th>
                  <th>E-mail</th>
                  <th>Tel. Principal</th>
                  <th>Cidade</th>
                  <th>Estado</th>
                  <th>Status</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
</section>
<script>
	
function delete_itens_list(){

	var check_item = $('.check_item');

if(!check_item.is(':checked')){
new PNotify({title: 'Nenhum item selecionado!',text: 'Selecione o item da lista que deseja excluir.',type: 'error'});
}else{ $('#confirm_delete').slideDown('slow'); }
}//end delete_itens_list


function delete_confirmed_itens_list(){

var check_item = $('.check_item');
if(check_item.is(':checked')){
items_checked = new Array();
$("input[type=checkbox][class='check_item']:checked").each(function(){
    items_checked.push($(this).val());
});
    var params = 'target=contributors&exec=delete_itens_selected&ids_delete='+items_checked;
	open_target(params);
}else{
new PNotify({title: 'Nenhum item selecionado!',text: 'Selecione o item da lista que deseja excluir.',type: 'error'});	
}

}//End delete_confirmed_itens_list



</script>





<script>
  $(function () {
    $('#contributors').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })

   $('#contributors_filter').append('<div class="btn-group"><button type="button" class="btn btn-primary" id="new_register"><i class="fa fa-plus"></i> Novo</button><button type="button" class="btn btn-primary" onclick="delete_itens_list();"><i class="fa fa-trash"></i> Excluir</button></div><p style="display:none" id="confirm_delete"><strong><a class="text-danger" href="javascript:void()" onclick="delete_confirmed_itens_list();">Confirmar exclusão dos itens selecionados!</a></strong></p>');


    $('input[type="search"]').removeClass('input-sm');
    $('select[name="contributors_length"]').removeClass('input-sm');
    

    $('#contributors_filter').on('click', '#new_register', function() {
    	open_target('target=contributor&id=0');
    	/* Act on the event */
    });

 })
</script>
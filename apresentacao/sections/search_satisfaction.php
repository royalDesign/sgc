<?php
if (!empty($_POST['exec'])) {

    switch ($_POST['exec']) {

        case 'save_search_satisfaction': {

                $id = $_POST['id'];

                $query = "SELECT * FROM sgc_" . $user['customer_code'] . "_search_satisfactions WHERE id = :id";
                $search = conecta()->prepare($query);
                $search->bindValue(':id', $id);
                $search->execute();
                $row_up = $search->fetch(PDO::FETCH_ASSOC);


                $data = array();
                $data['title'] = trim(strip_tags($_POST['title']));
                $data['status'] = trim(strip_tags($_POST['status']));
                $data['number_questions'] = trim(strip_tags($_POST['number_questions']));

                $data['date_send'] = sgc_date_format(trim(strip_tags($_POST['date_send'])), 'Y-m-d');
                $data['date_limit'] = sgc_date_format(trim(strip_tags($_POST['date_limit'])), 'Y-m-d');

                if ($id) {
                    $data['modified_date'] = date('Y-m-d H:i:s');
                    $data['modified_user'] = $user['id'];
                } else {
                    $data['created_date'] = date('Y-m-d H:i:s');
                    $data['created_user'] = $user['id'];
                }
                $ret = sgc_save_db('sgc_' . $user['customer_code'] . '_search_satisfactions', $data, $id);

                if (!$ret['error_number']) {
                    if ($ret['new_id']) {
                        $_POST['id'] = $ret['new_id'];
                    }
                    echo "<script>new PNotify({title: 'Sucesso!',text: 'Dados salvos com sucesso.',type: 'success'});</script>";
                } else {
                    //print_r($ret);
                    echo "<script>new PNotify({title: 'Error!',text: 'Não foi possível realizar esta operação.',type: 'error'});</script>";
                }

                if ($id) {
//atualização da linha do tempo
                    if ($row_up['title'] != $data['title']) {
                        $data_timeline = array();
                        $data_timeline['created_user'] = $user['id'];
                        $data_timeline['created_date'] = date('Y-m-d H:i:s');
                        $data_timeline['item_id'] = $id;
                        $data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
                        $data_timeline['title'] = 'Atualizou esta categoria';
                        $data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Título</strong></p> <strong>De</strong> ' . $row_up['name'] . ' <br><strong>para</strong> ' . $data['title'];
                        $new_timeline = sgc_save_db('sgc_' . $user['customer_code'] . '_search_satisfactions_updates', $data_timeline, 0);
                    }



                    if ($row_up['status'] != $data['status']) {
                        $up_from = ($row_up['status'] == 1) ? 'Ativo' : 'Inativo';
                        $up_to = ($data['status'] == 1) ? 'Ativo' : 'Inativo';
                        $data_timeline = array();
                        $data_timeline['created_user'] = $user['id'];
                        $data_timeline['created_date'] = date('Y-m-d H:i:s');
                        $data_timeline['item_id'] = $id;
                        $data_timeline['icon'] = '<i class="fa fa-book bg-blue"></i>';
                        $data_timeline['title'] = 'Atualizou esta categoria';
                        $data_timeline['content'] = '<p style="color: #3c8dbc"><strong>Status</strong></p> <strong>De</strong> ' . $up_from . ' <br><strong>para</strong> ' . $up_to;
                        $new_timeline = sgc_save_db('sgc_' . $user['customer_code'] . '_search_satisfactions_updates', $data_timeline, 0);
                    }
                }//END IF ID
            }break;


        case 'save_questions': {



                $x = $_POST['x'];
                $id_insert = $_POST['id_question_' . $x];

                $questions = trim($_POST['questions_response_' . $x]);
                $questions = explode(PHP_EOL, $questions);
                $questions = json_encode($questions);
                /*
                  echo "<pre>";
                  //print_r($_POST);
                  echo $questions;
                  echo "</pre>";
                 */


                $data = array();
                $data['content'] = $_POST['content_' . $x];
                $data['type'] = $_POST['type_response_' . $x];
                $data['position'] = $x;
                $data['questions'] = $questions;
                $data['created_user'] = $user['id'];
                $data['created_date'] = date('Y-m-d H:i:s');
                $data['search_satisfactions_id'] = $_POST['id'];

                $ret = sgc_save_db('sgc_' . $user['customer_code'] . '_search_satisfactions_questions', $data, $id_insert);



                if (!$ret['error_number']) {
                    echo "<script>new PNotify({title: 'Sucesso!',text: 'Perguntas salvas com sucesso.',type: 'success'});</script>";
                } else {
                    echo "<script>new PNotify({title: 'Error!',text: 'Não foi possível realizar esta operação.',type: 'error'});</script>";
                }
            }
            break;




        case 'delete_question': {

                /*
                  echo "<pre>";
                  print_r($_POST);
                  echo "</pre>";
                 */

//deletando o selecionado
                $query = "DELETE FROM sgc_" . $user['customer_code'] . "_search_satisfactions_questions WHERE id = :id";
                $exec = conecta()->prepare($query);
                $exec->bindValue(':id', $_POST['question_id']);
                $exec->execute();

                $query = "UPDATE sgc_" . $user['customer_code'] . "_search_satisfactions SET number_questions = number_questions -1 WHERE id = :id";
                $exec = conecta()->prepare($query);
                $exec->bindValue(':id', $_POST['id']);
                $exec->execute();


                if ($_POST['x'] < $_POST['qtd_questions']) {

                    $query = "UPDATE sgc_" . $user['customer_code'] . "_search_satisfactions_questions SET position = position -1 WHERE position > :position_x AND search_satisfactions_id = :id";
                    $exec = conecta()->prepare($query);
                    $exec->bindValue(':position_x', $_POST['x']);
                    $exec->bindValue(':id', $_POST['id']);
                    $exec->execute();
                }
            }break;


        case 'search_user': {
                require_once "../class/sgc_functions.php";
                $search = trim(strip_tags($_POST['search']));

                $sql = "SELECT id,name FROM users WHERE (name LIKE ? OR username LIKE ? OR email LIKE ?) AND customer_code = ?";
                $pst = conecta()->prepare($sql);
                $pst->bindValue(1, '%' . $search . '%');
                $pst->bindValue(2, '%' . $search . '%');
                $pst->bindValue(3, '%' . $search . '%');
                $pst->bindValue(4, $_POST['customer_code']);
                $pst->execute();
                $ret = $pst->fetchAll(PDO::FETCH_ASSOC);



                $retorno = '';
                if (count($ret) > 0) {
                    $retorno .= '<div class="form-group">';
                    $retorno .= '<select name="select_user" multiple onchange="user_selected(this);" id="select_user" class="form-control" style="margin-top: 15px;">';
                    $retorno .= '<option value="">--Selecione--</option>';

                    foreach ($ret as $key => $value) {
                        $retorno .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
                    }
                    $retorno .= '</select>';
                    $retorno .= '</div>';
                } else {
                    echo "<script>new PNotify({title: 'Ops',text: 'Nenhum usuário localizado.',type: 'info'});</script>";
                }
                exit($retorno);
            }break;//end case


        case 'set_user_view_satisfaction': {

                $data = array();
                $data['created_date'] = date('Y-m-d');
                $data['created_user'] = $user['id'];
                $data['user_id'] = $_POST['user_id'];
                $data['search_satisfaction_id'] = $_POST['id'];


                $ret = sgc_save_db('sgc_' . $user['customer_code'] . '_search_satisfactions_acl', $data, 0);

                if (!$ret['error_number']) {
                    echo "<script>new PNotify({title: 'Sucesso!',text: 'Pesmissão para visualização adicionada com sucesso.',type: 'success'});</script>";
                    echo "<script>$('#control_cad').removeClass('active'); $('#control_permissions').addClass('active'); $('#permissions').addClass('active'); $('#cadastro').removeClass('active');</script>";
                } else {
                    echo "<script>new PNotify({title: 'Ops!',text: 'Não foi possível realizar esta operação.',type: 'error'});</script>";
                    echo "<script>$('#control_cad').removeClass('active'); $('#control_permissions').addClass('active'); $('#permissions').addClass('active'); $('#cadastro').removeClass('active');</script>";
                }
            }break; //end Case
            
        case 'remove_user_permissions':
        {
            $data_del = array();
            $data_del[] = strip_tags(trim($_POST['id']));
            $data_del[] = strip_tags(trim($_POST['id_user_del']));
            $query = "DELETE FROM sgc_".$user['customer_code']."_search_satisfactions_acl WHERE search_satisfaction_id = ? AND user_id = ?";
            $pst = conecta()->prepare($query);
            $ret = $pst->execute($data_del);           
        
           if($ret){
             echo "<script>new PNotify({title: 'Sucesso!',text: 'Pesmissão para visualização removida com sucesso',type: 'success'});</script>";
             echo "<script>$('#control_cad').removeClass('active'); $('#control_permissions').addClass('active'); $('#permissions').addClass('active'); $('#cadastro').removeClass('active');</script>";  
           }
           
        }break;//END case
    }
}


$query = "SELECT a.*,u.name AS name_created,um.name AS name_modified FROM sgc_" . $user['customer_code'] . "_search_satisfactions AS a LEFT JOIN users AS u ON u.id = a.created_user LEFT JOIN users AS um ON um.id = a.modified_user WHERE a.id = :id";
$search = conecta()->prepare($query);
$search->bindValue(':id', $_POST['id']);
$search->execute();
$row = $search->fetch(PDO::FETCH_ASSOC);


//timeline
$query = "SELECT a.*,u.name AS name_user FROM sgc_" . $user['customer_code'] . "_search_satisfactions_updates AS a LEFT JOIN users AS u ON u.id = a.created_user WHERE a.item_id = :id ORDER BY a.id DESC";
$search = conecta()->prepare($query);
$search->bindValue(':id', $_POST['id']);
$search->execute();
$rows_timeline = $search->fetchAll(PDO::FETCH_ASSOC);

//select dos usuários individuais com permições
$id = trim(strip_tags($_POST['id']));
$query = "SELECT a.id,a.name,sc.user_id FROM users AS a LEFT JOIN sgc_apresentacao_search_satisfactions_acl AS sc ON sc.user_id = a.id WHERE sc.search_satisfaction_id = ? AND a.customer_code = ? GROUP BY a.id ORDER BY a.name";
$search = conecta()->prepare($query);
$search->execute(array($id, $user['customer_code']));
$rows_users_permissions = $search->fetchAll(PDO::FETCH_ASSOC);
$total_users_permissions = count($rows_users_permissions);
?>
<section class="content-header">
    <h1>Pesquisa de satisfação <a id="refresh_page" href="javascript:void()" onclick="open_target('target=search_satisfaction&id=<?php echo $row['id'] ?>', $(this))"><i class="fa fa-refresh"></i></h1>
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
                    <li class="active" id="control_cad"><a href="#cadastro" data-toggle="tab">Cadastro</a></li>
                    <li id="control_permissions"><a href="#permissions" data-toggle="tab">Permissões</a></li>
                    <li><a href="#timeline" data-toggle="tab">Atualizações</a></li>              
                </ul>
                <div class="tab-content">




                    <div class="tab-pane active" id="cadastro">
                        <form role="form" action="" method="POST" name="form_main" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="id" value="<?php echo $row['id']; ?>">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="title">Nome</label>
                                            <input type="text"  name="title" autocomplete="off"  class="form-control" id="title" placeholder="Título da pesquisa" value="<?php echo $row['title'] ?>">
                                        </div>
                                    </div>

                                </div><!--END ROW-->


                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="title">Número de questões</label>
                                            <input type="number" min="1"  name="number_questions" autocomplete="off"  class="form-control" id="number_questions" value="<?php echo $row['number_questions'] ?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="date_send">Data de envio</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" value="<?php echo sgc_date_format($row['date_send'], 'd/m/Y'); ?>" OnKeyPress="return sgc_masc_fild(event, this, '##/##/####');"  name="date_send" id="date_send" class="form-control pull-right">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="date_limit">Data limite</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" value="<?php echo sgc_date_format($row['date_limit'], 'd/m/Y'); ?>" OnKeyPress="return sgc_masc_fild(event, this, '##/##/####');"  name="date_limit" id="date_limit" class="form-control pull-right">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="status">Status:</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="">--Selecione--</option>
                                                <option <?php echo ($row['status'] == '1') ? 'selected' : ''; ?> value="1">Ativo</option>
                                                <option <?php echo ($row['status'] == '0') ? 'selected' : ''; ?> value="0">Inativo</option>
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

                    <div class="tab-pane" id="permissions">
                        <div class="box-body">

                            <div class="row">
                                <div class="col-sm-12">
                                    <h4>Buscar por usuário</h4>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group input-group-md">
                                        <input class="form-control" type="text" id="text_search" name="text_search" placeholder="Buscar por nome, e-mail ou usuário">
                                        <span class="input-group-btn">
                                            <button type="button" id="load_user" class="btn btn-default btn-flat" onclick="sgc_search_user_permissions();"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-12" id="search_user_p">

                                </div>

                            </div>
                            <p></p>



                            <?php if ($total_users_permissions == 0): ?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p>Nenhum usuário adicionado</p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php foreach ($rows_users_permissions as $row_users_permissions): ?>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p><span class="label label-info"><?php echo $row_users_permissions['name'] ?></span> <a id="link_del_<?php echo $row_users_permissions['id'];?>" href="javascript:void(0);" onclick="sgc_toggle('deleted_item_<?php echo $row_users_permissions['id'];?>')"><span class="label label-danger"><i class="fa fa-close"></i></span></a></p>
                                            <p style="display: none;" id="deleted_item_<?php echo $row_users_permissions['id']?>"><a href="javascript:void(0)" onclick="confirm_remove_user_permissions(<?php echo $row_users_permissions['user_id'];?>,$(this));" class="text-danger" >Confirmar remoção deste usuário!</a></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <hr>
                        </div>
                    </div><!--FIN DA ABA PERMISSÕES-->





                    <div class="tab-pane" id="timeline">
                        <!-- The timeline -->
                        <ul class="timeline timeline-inverse">

                            <?php if (count($rows_timeline) == 0): ?>

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
                                Criado por <br><a><?php echo $row['name_created']; ?></a> em <a><?php echo sgc_date_format($row['created_date'], 'd/m/Y H:i'); ?></a>
                            </li>

                            <?php if ($row['modified_date']): ?>
                                <li class="list-group-item">
                                    Atualizado por<br> <a><?php echo $row['name_modified']; ?></a> em <a><?php echo sgc_date_format($row['modified_date'], 'd/m/Y H:i'); ?></a>
                                </li>     
                            <?php endif; ?>           
                        </ul>						


                    </div>            
                </div>
            </div>
        <?php endif; ?>
    </div><!-- /.row 7/5 -->




    <?php if ($row['id']): ?>
        <div class="content box box-primary">



            <input type="hidden" name="id" id="id" value="<?php echo $row['id']; ?>">
            <input type="hidden" name="qtd_questions" id="qtd_questions" value="<?php echo $row['number_questions']; ?>"> 


            <div class="row">
                <div class="col-sm-6"><h4>Edição de perguntas</h4><hr></div>
                <div class="col-sm-6"><h4>Pré-visualização de perguntas</h4><hr></div>
            </div>


            <?php for ($x = 1; $x <= $row['number_questions']; $x++): ?>
                <?php
//Consultar as perguntas
                $xy = $x - 1;
                $query = "SELECT a.content,a.created_date,a.id,a.type,a.questions FROM sgc_" . $user['customer_code'] . "_search_satisfactions_questions AS a WHERE a.search_satisfactions_id = :id ORDER BY a.id ASC LIMIT " . $xy . "," . $row['number_questions'];
                $search = conecta()->prepare($query);
                $search->bindValue(':id', $row['id']);
                $search->execute();
                $res = $search->fetch(PDO::FETCH_ASSOC);
                $responde_fild = '';
                if (($res['type'] == 1 || $res['type'] == 2) && $res['questions']) {
                    $responde_fild = json_decode($res['questions']);
                    $responde_fild = implode(PHP_EOL, $responde_fild);

                    $responde_preview = json_decode($res['questions']);
                }

//$error = $search->errorInfo();
//print_r($error);
                ?>





                <div class="row" style="margin-bottom:35px;">
                    <div class="col-md-6">
                        <form role="form" action="" method="POST" name="form_question_<?php echo $x; ?>" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-12">

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="title"><?php echo $x; ?> º) Questão</label>
                                                <textarea  name="content_<?php echo $x; ?>" id="content_<?php echo $x; ?>" class="form-control"><?php echo $res['content'] ?></textarea>

                                            </div>
                                        </div>
                                    </div><!--END ROW-->

                                    <div class="row">
                                        <div class="col-sm-12">     
                                            <div class="form-group">
                                                <label for="title">Tipo de resposta</label>
                                                <select onchange= "hide_resp(this,<?php echo $x; ?>);" class="form-control" name="type_response_<?php echo $x; ?>" id="type_response_<?php echo $x; ?>">

                                                    <option value="1" <?php echo ($res['type'] == 1) ? 'selected="selected"' : ''; ?>>Única escolha</option>
                                                    <option value="2" <?php echo ($res['type'] == 2) ? 'selected="selected"' : ''; ?>>Múltipla escolha</option>
                                                    <option value="3" <?php echo ($res['type'] == 3) ? 'selected="selected"' : ''; ?>>Texto simples</option>
                                                    <option value="4" <?php echo ($res['type'] == 4) ? 'selected="selected"' : ''; ?>>Texto longo</option>                  
                                                </select>
                                            </div>       
                                        </div>
                                    </div>


                                    <div class="row" id="hide_show_response_<?php echo $x; ?>" style="display: <?php echo ($res['type'] == 1 || $res['type'] == 2) ? 'block' : 'none'; ?>">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <textarea class="form-control" name="questions_response_<?php echo $x; ?>" id="questions_response_<?php echo $x; ?>" rows="10"><?php echo $responde_fild; ?></textarea>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-6">
                                            <button type="button" name="sgc_save" id="sgc_save" onclick="sgc_save_questions($(this),<?php echo $x; ?>);" class="btn btn-primary btn-sm btn_action"><i class="fa fa-check"></i> Salvar</button>
                                        </div>



                                        <div class="col-sm-6 text-right">
                                            <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="sgc_delete_item('item_content_<?php echo $res['id'] ?>');">Excluir <i class="fa fa-trash"></i></a>
                                            <p style="display: none;" class="item_content_<?php echo $res['id'] ?>"><a href="javascript:void(0)" onclick="sgc_delete_item_confirmed('<?php echo $res['id'] ?>',<?php echo $x; ?>, $(this));" class="text-danger">Confirmar exclusão</a>
                                        </div>
                                    </div>    

                                    <input type="hidden" value="<?php echo ($res['id']) ? $res['id'] : '0'; ?>" id="id_question_<?php echo $x; ?>" name="id_question_<?php echo $x; ?>">

                                </div><!--col-12 GERAL-->
                            </div><!--LINHA GERAL-->



                        </form>

                    </div><!--fechando peimeiro 6-->




                    <div class="col-sm-6">

                        <div class="row">
                            <div class="col-sm-12">
                                <h4><?php echo $res['content']; ?></h4>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-12">

                                <?php if ($res['type'] == 1): ?>
                                    <?php foreach ($responde_preview AS $key => $values_questions): ?>

                                        <div class="radio">
                                            <label>
                                                <input name="optionsRadios" id="optradios_<?php echo $key + 1; ?>" value="<?php echo $key + 1; ?>" type="radio">
                                                <?php echo $values_questions; ?>
                                            </label>
                                        </div>                  
                                    <?php endforeach; ?>

                                <?php elseif ($res['type'] == 2): ?>
                                    <?php foreach ($responde_preview AS $key => $values_questions): ?>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="optcheckbox_<?php echo $key + 1; ?>" value="<?php echo $key + 1; ?>">
                                                <?php echo $values_questions; ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php elseif ($res['type'] == 3): ?>

                                    <input class="form-control" placeholder="Informe sua resposta" type="text" name="opttext" id="opttext">

                                <?php elseif ($res['type'] == 4): ?>

                                    <textarea name="oprtextarea" id="oprtextarea" placeholder="Informe sua resposta" rows="5" class="form-control"></textarea>

                                <?php endif; ?>

                            </div>
                        </div>

                    </div>





                </div><!--Linha GERAL-->

            <?php endfor; ?>







        </div>
    <?php endif; ?>

</section>

<!-- /.content -->
</div>

<script>

    function sgc_save_main(btn) {

        var form = $('form[name="form_main"]');
        var title = $('#title');
        var status = $('#status');
        var description = $('#description');
        var number_questions = $('#number_questions');
        var date_send = $('#date_send');
        var date_limit = $('#date_limit');
        var params = 'target=search_satisfaction&exec=save_search_satisfaction&' + form.serialize();

        if (valid_fild('required', title) && valid_fild('select', status) && valid_fild('int', number_questions) && valid_fild('required', date_send) && valid_fild('required', date_limit)) {
            open_target(params, btn);
        }

    }


    function sgc_save_questions(btn, x) {

        var form = $('form[name="form_question_' + x + '"]');

        var id = $('#id').val();
        var id_question = $('#id_question_' + x).val();
        var content = $('#content_' + x).val();
//var params  = 'target=search_satisfaction&exec=save_questions&id='+id+'&x='+x+'&id_question='+id_question+'&content='+content;
        var params = 'target=search_satisfaction&exec=save_questions&id=' + id + '&x=' + x + '&' + form.serialize();

        open_target(params, btn);

    }


    function sgc_delete_item(item_toggle) {

        $('.' + item_toggle).toggle('slow');

    }

    function sgc_delete_item_confirmed(question_id, x, btn) {

        var id = $('#id').val();
        var qtd_questions = $('#qtd_questions').val();
        open_target('target=search_satisfaction&exec=delete_question&id=' + id + '&question_id=' + question_id + '&qtd_questions=' + qtd_questions + '&x=' + x, btn);
    }

    function hide_resp(obj, x) {


        if (parseInt(obj.value) == 1 || parseInt(obj.value) == 2) {

            $('#hide_show_response_' + x).slideDown('slow');

        } else {

            $('#hide_show_response_' + x).slideUp('slow');

        }

    }


    function sgc_search_user_permissions() {

        var text_search = $('#text_search').val();
        var response = $('#search_user_p');
        var params = 'exec=search_user&search=' + text_search + '&customer_code=<?php echo $user['customer_code']; ?>';

        $.ajax({

            url: 'sections/search_satisfaction.php',
            type: 'post',
            dataType: 'html',
            data: params,
            error: function (retorno) {
                console.log('erro inesperado' + retorno);
            },

            beforeSend: function () {
                $('#load_user').html('<img src="img/load_b.svg" width="18px">');
            },

            success: function (retorno) {

                response.html(retorno);
                $('#load_user').html('<i class="fa fa-search"></i>');
            },

            complete: function () {

                //$('#load_user').html('<i class="fa fa-search"></i>');
            }





        });



    }


    function user_selected(obj) {

        var id_user = obj.value;
        var params = 'target=search_satisfaction&exec=set_user_view_satisfaction&id=<?php echo $_POST['id']; ?>&user_id=' + id_user;

//console.log(params);
        open_target(params);

    }
    
    function confirm_remove_user_permissions(id_del,obj){
        
        var params = 'target=search_satisfaction&exec=remove_user_permissions&id_user_del='+id_del+'&id=<?php echo $row['id'];?>';
        open_target(params,obj);    
    
    }
    


    $('#date_send').datepicker({
        autoclose: true,
        language: 'br'
    });


    $('#date_limit').datepicker({
        autoclose: true,
        language: 'br'
    });

</script>

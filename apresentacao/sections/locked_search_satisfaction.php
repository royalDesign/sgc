<?php require_once "../class/sgc_functions.php"; ?>
<?php
    if (!empty($_POST['exec'])) {
        switch ($_POST['exec']) {

            case'sgc_save_view_questions': {

                $control = $_POST['total_questions'];
                $arr = array();
                for ($x=1;$x<=$control;$x++){
                 $type = $_POST['type_question_'.$x];
                 
                 if($type == 2){
                     $data_save = array();
                     $data_save['question_type'] = $type;
                     $data_save['question_selected'] = trim($_POST['response_radio_'.$x]);
                     $data_save['question_text_selected'] = trim($_POST['text_options_save_'.$x]);
                     $data_save['question_text'] = $_POST['pergunta_'.$x];
                 }//fim tipo 2 radio
                    
                }

                    exit(print_r($data_save));
                    
                    
                }break;
        }//end switch
    }//end isset

 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>CSO | Painel de Controle</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="../bower_components/datatables_net_bs/css/dataTables.bootstrap.min.css">


        <link rel="stylesheet" href="../dist/css/skins/skin-blue.min.css">


        <link rel="stylesheet" href="../bower_components/bootstrap_daterangepicker/daterangepicker.css">
        <!-- bootstrap datepicker -->
        <link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

        <!--Pnotfy-->
        <link rel="stylesheet" href="../css/pnotify.custom.min.css">
        <link rel="stylesheet" href="../css/animate.css">

        <!-- Google Font -->
        <link rel="stylesheet" href="../https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" href="../bower_components/select2/dist/css/select2.css">

    </head>
    
<?php
    $query = "SELECT ss.title,ss.id,ss.number_questions FROM sgc_apresentacao_search_satisfactions AS ss LEFT JOIN sgc_apresentacao_search_satisfactions_acl AS sacl ON ss.id = sacl.search_satisfaction_id WHERE (DATE(?) BETWEEN DATE(ss.date_send) AND DATE(ss.date_limit)) AND sacl.user_id = ? AND sacl.view_status = 1 AND ss.status = 1";
    $pst = conecta()->prepare($query);
    $pst->execute(array(date('Y-m-d'), $user['id']));
    $res = $pst->fetch(PDO::FETCH_ASSOC);


    $query = "SELECT * FROM sgc_apresentacao_search_satisfactions_questions WHERE search_satisfactions_id = ?";
    $pst = conecta()->prepare($query);
    $pst->execute(array($res['id']));
    $rows_questions = $pst->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <body class="hold-transition lockscreen">
        <div class="col-sm-8 col-md-offset-2">
            <div class="box">
                <div class="box-header with-border">
                    <h2 class="box-title"><?php echo $res['title'] ?></h2>                   
                </div>
                <div class="box-body">
                    <form name="question_responses" method="POST">

                        <input type="hidden" name="total_questions" id="total_questions" value="<?php echo $res['number_questions'] ?>" />
                        <input type="hidden" name="current_question" id="current_question" value="1"/>
                        <input type="hidden" name="id_search_satisfaction" id="id_search_satisfaction" value="<?php echo $res['id'];?>" />
<?php foreach ($rows_questions as $key => $value): ?>
<input type="hidden" name="type_question_<?php echo $key + 1; ?>" id="type_question_<?php echo $key + 1; ?>" value="<?php echo $value['type']; ?>"/>

    <?php
    if (($value['type'] == 1 || $value['type'] == 2) && $value['questions']) {
        $response_fild = json_decode($value['questions']);
    }
    ?>
                            <div style="display: <?php echo ($key + 1 == 1) ? 'block' : 'none'; ?>" class="row question_complet_<?php echo $key + 1; ?>">
                                <div class="col-sm-12">
                                    <h4><?php echo $value['content']; ?></h4>
                                    


                            <?php if ($value['type'] == 1): ?>
                                <?php foreach ($response_fild AS $key_int => $values_questions): ?>

                                            <div class="radio">
                                                <label>
                                             <input name="optionsRadios_<?php echo $key + 1; ?>" id="optradios_<?php echo $key_int + 1; ?>" value="<?php echo $key_int + 1; ?>" type="radio">
                                            <?php echo $values_questions; ?>
                                             <input type="hidden" name="text_options_save_<?php echo $key_int+1;?>" value="<?php echo $values_questions; ?>"  />
                                                </label>
                                            </div>                  
                                            <?php endforeach; ?>

                                            <?php elseif ($value['type'] == 2): ?>
                                                <?php foreach ($response_fild AS $key_int => $values_questions): ?>
                                            <div class="checkbox">
                                                <label>
                   <input type="hidden" name="pergunta_<?php echo $key+1;?>" value="<?php echo $value['content']; ?>"  />
                                                    <input type="checkbox" name="optcheckbox_<?php echo $key + 1; ?>" value="<?php echo $key_int + 1; ?>">
                                                   <?php echo $values_questions; ?>
                                                </label>
                                            </div>
        <?php endforeach; ?>
    <?php elseif ($value['type'] == 3): ?>

                                        <input class="form-control" placeholder="Informe sua resposta" type="text" name="opttext_<?php echo $key + 1; ?>" id="opttext_<?php echo $key + 1; ?>">

                                    <?php elseif ($value['type'] == 4): ?>

                                        <textarea name="oprtextarea_<?php echo $key + 1; ?>" id="oprtextarea_<?php echo $key + 1; ?>" placeholder="Informe sua resposta" rows="5" class="form-control"></textarea>

    <?php endif; ?>
                                </div>
                            </div>


                                <?php endforeach; ?>
                    </form>
                    <p id="text_alert" class="text-danger"></p>
                </div>
                <!-- /.box-body -->
                <div class="box-footer" style="">
                    <button style="display:none" name="btn_prev" id="btn_prev" onclick="sgc_btn_prev();" class="btn btn-sm btn-default"/><i class="fa fa-arrow-left"></i> Voltar</button>  
                    <button  name="btn_next" id="btn_next" onclick="sgc_btn_next();" class="btn btn-sm btn-success"/>Responder <i class="fa fa-arrow-right "></i></button>

                </div>
                <!-- /.box-footer-->
            </div>
        </div>

        <script>
            function sgc_btn_next() {
                var current_question = $('#current_question').val();
                var type = $('#type_question_' + current_question).val();
                var total_questions = $('#total_questions').val();
                var increment = 1;
                var valid = false;

                if (type == 1) {

                    var radiochecked = $("input[name='optionsRadios_" + current_question + "']:checked").val();
                    if (radiochecked) {
                        valid = true;
  $('form[name="question_responses"]').append('<input name="response_radio_'+current_question+'" id="response_radio_'+current_question+'" value="'+radiochecked+'" type="hidden">');
                    } else {
                        $('#text_alert').text('Sua resposta é muito importante para nós.');
                    }

                } else if (type == 2) {
                    var checkbox = new Array();
                    $("input[type=checkbox][name='optcheckbox_" + current_question + "']:checked").each(function () {
                        checkbox.push($(this).val());
                    });

                    if (checkbox != '') {
                        valid = true;
$('form[name="question_responses"]').append('<input name="response_array_'+current_question+'" id="response_array_'+current_question+'" value="'+checkbox+'" type="hidden">');
                    } else {
                        $('#text_alert').text('Sua resposta é muito importante para nós.');
                    }


                } else if (type == 3) {
                    var text_resp = $('#opttext_' + current_question).val();
                    if (text_resp != '') {
                        valid = true;
                    } else {
                        $('#text_alert').text('Sua resposta é muito importante para nós.');
                    }

                } else if (type == 4) {
                    var textarea_resp = $('#oprtextarea_' + current_question).val();
                    if (textarea_resp != '') {
                        valid = true;
                    } else {
                        $('#text_alert').text('Sua resposta é muito importante para nós.');
                    }
                }//END else valid


                if (valid) {
                    $('#text_alert').empty();
                    $('#btn_prev').fadeIn('fast');
                    $('.question_complet_' + current_question).slideUp('slow', function () {


                        if (parseInt(current_question) + 1 === parseInt(total_questions)) {
                            $('#btn_next').empty().html('Enviar respostas <i class="fa fa-check"></i>');
                        }

                        if (parseInt(current_question) === parseInt(total_questions)) {
                            $('#text_alert').empty().removeClass('text-danger').addClass('text-success').html('<i class="fa fa-check"></i> Obrigado por sua resposta, você será redirecionado ao sistema! <i id="load_save_resp"></i>');


                            var form = $('form[name="question_responses"]');
                            var params = 'exec=sgc_save_view_questions&' + form.serialize();

                            $.ajax({

                                url: 'locked_search_satisfaction.php',
                                type: 'post',
                                dataType: 'html',
                                data: params,
                                error: function (retorno) {
                                    console.log('erro inesperado' + retorno);
                                },

                                beforeSend: function () {
                                    $('#load_save_resp').html('<img src="../img/load_b.svg" width="18px">');
                                },

                                success: function (retorno) {

                                    console.log(retorno);
                                    $('#load_save_resp').empty();
                                    $('#btn_next').fadeOut('fast');
                                    $('#btn_prev').fadeOut('fast');
                                },

                                complete: function () {

                                    //$('#load_user').html('<i class="fa fa-search"></i>');
                                }

                            });


                        } else {
                            $('#current_question').val(parseInt(current_question) + 1);
                            $('.question_complet_' + $('#current_question').val()).slideDown('slow');
                        }

                    });

                }//end if next

            }//END FUNCTION    


            function sgc_btn_prev() {
                $('#text_alert').empty();

                var current_question = $('#current_question').val();
                var type = $('#type_question_' + current_question).val();

                if (parseInt(current_question) - 1 !== parseInt(total_questions)) {
                    $('#btn_next').empty().html('Responder <i class="fa fa-arrow-right"></i>');
                }

                if (parseInt(current_question) - 1 == 1) {
                    $('#btn_prev').fadeOut('fast');
                }

                if (parseInt(current_question) - 1 != 0) {
                    $('.question_complet_' + current_question).slideUp('slow', function () {
                        $('#current_question').val(parseInt(current_question) - 1);
                        $('.question_complet_' + $('#current_question').val()).slideDown('slow');
                    });

                }

            }

        </script>




        <!-- REQUIRED JS SCRIPTS -->

        <!-- jQuery 3 -->
        <script src="../bower_components/jquery/dist/jquery.min.js"></script>
        <!-- AdminLTE App -->
        <script src="../dist/js/adminlte.min.js"></script>

        <!-- Bootstrap 3.3.7 -->
        <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

        <script src="../bower_components/select2/dist/js/select2.full.min.js"></script>

        <script src="../js/jquery.form.js"></script>
        <!-- date-range-picker -->
        <script src="../bower_components/moment/min/moment.min.js"></script>
        <script src="../bower_components/bootstrap_daterangepicker/daterangepicker.js"></script>

        <script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.br.min.js"></script>

        <script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="../bower_components/fastclick/lib/fastclick.js"></script>
        <!--
        <script src="../dist/js/demo.js"></script>
        -->
        <script src="../bower_components/jquery-knob/js/jquery.knob.min.js"></script>
        <script src="../bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
        <script src="../bower_components/chart_js/Chart.js"></script>
        <!-- DataTables -->
        <script src="../bower_components/datatables_net/js/jquery.dataTables.js"></script>
        <script src="../bower_components/datatables_net_bs/js/dataTables.bootstrap.js"></script>



        <script type="text/javascript" src="../js/pnotify.custom.min.js"></script>
        <script type="text/javascript" src="../js/jquery.maskMoney.min.js"></script>
        <script type="text/javascript" src="../js/sgc_functions.js"></script>






    </body>
</html>



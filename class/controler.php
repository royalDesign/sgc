<?php 
require_once "sgc_functions.php";

if(!empty($_POST['exec'])){

switch ($_POST['exec']) {

case 'logar':
{
$data = array();
$data[] = date('Y-m-d');
$data[] = strip_tags(trim($_POST['email']));
$data[] = strip_tags(trim($_POST['email']));
$data[] = trim(md5($_POST['senha']));

$query = "SELECT u.*,(SELECT COUNT(*) FROM sgc_apresentacao_search_satisfactions AS ss LEFT JOIN sgc_apresentacao_search_satisfactions_acl AS sacl ON ss.id = sacl.search_satisfaction_id WHERE (DATE(?) BETWEEN DATE(ss.date_send) AND DATE(ss.date_limit)) AND sacl.user_id = u.id AND sacl.view_status = 1 AND ss.status = 1) AS pesquisa FROM users AS u WHERE (u.email = ? OR u.username = ?) AND u.password = ?";
$valid_log = conecta()->prepare($query);
$erro = $valid_log->errorInfo();
$valid_log->execute($data);
$row = $valid_log->fetch();
$result = $valid_log->rowCount();

if($row['pesquisa'] > 0){
    
    $_SESSION['locked_pesquisa'] = 1;
}

if($result > 0){
	foreach ($row as $key => $value){
		if(!is_numeric($key) && $key != 'password_show')
			$_SESSION[$key] = $value;
	}
redirect($row['customer_code']);


}else{
	echo '-1';
}

}break;



	
	default:
		echo "Rotina não configurada";
		break;
}/*END ROTINAS EXEC*/
}/*END IF ROTINAS EXEC*/

?>
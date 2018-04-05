<?php 
require_once "sgc_functions.php";

if(!empty($_POST['exec'])){

switch ($_POST['exec']) {

case 'logar':
{
$data = array();
$data[] = strip_tags(trim($_POST['email']));
$data[] = trim(md5($_POST['senha']));

$query = "SELECT * FROM users WHERE email = ? AND password = ?";
$valid_log = conecta()->prepare($query);
$erro = $valid_log->errorInfo();
$valid_log->execute($data);
$row = $valid_log->fetch();
$result = $valid_log->rowCount();

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
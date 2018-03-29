<?php
session_start();
if(!empty($_SESSION)){
$user = array();
   foreach ($_SESSION as $key => $value) {
      $user[$key] = $value;
   }
   
}

function conecta(){ 
   //CONEXAO OFLLINE

   $host = "localhost";
   $usuario = "root";
   $senha = "";
   $bandoDados = "sgc";

   //CONEXAO 000WEBHOST
/*
   $host = "localhost";
   $usuario = "id2664418_cso";
   $senha = "c9p5au8naa2017";
   $bandoDados = "id2664418_cso";
*/
   //MUTANT HOST
   /*
   $host = "fdb7.mutanthost.com";
   $usuario = "2508089_sigpress";
   $senha = "c9p5au8naa2017";
   $bandoDados = "2508089_sigpress";
*/
   try{
   $pdo = new PDO("mysql:host=$host;dbname=$bandoDados","$usuario","$senha");  
   
   }catch (PDOException $erro){
       echo"Erro ao conectar com o banco de dados".$erro->getMessage();
   }
   return $pdo;
}//end Conecta



function redirect($customer_code){
        
   echo"<script language='javascript'> window.location='".$customer_code."/'; </script>";        
    
}



?>
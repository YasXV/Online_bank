<?php session_start();
$id_client = $_SESSION["id_client"];
session_destroy();
//fuseau horaire de paris 
date_default_timezone_set('Europe/Paris');
//on set un cookie contenant le dernier instant de connexion
setcookie("derniere_co".$id_client,date('d-m-Y G:i:s'),time()+30*24*3600,'/banque');

//on laisse le temps au cookie de se mettre à jour
sleep(1);
//on redigire vers la page index.php
header('Location:../index.php');
exit();

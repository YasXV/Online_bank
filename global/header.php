<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
    <meta name="Bouh" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/styles_index.css">
    <link rel="stylesheet" type="text/css" href="css/style_nav_bar.css">
    <link rel="stylesheet" type="text/css" href="css/style_body_co_ins.css">
    <link rel="icon" href="../images/logo_bouh.png" type="image/x-icon">
<?php
require 'functions.php';
try {
    $bdd = new PDO(
    'mysql:host=localhost; dbname=banque;charset=utf8',
    'root',
    '',
    [PDO::ATTR_ERRMODE=> PDO::ERRMODE_EXCEPTION]
);
} catch (Exception $e){
    die('Erreur : '.$e->getMessage());// on kill le code
} // on peut ne pas fermer la balise php, car pas d'autres balises aprés 
?>
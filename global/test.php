<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>test ok</title>
  </head>
  <body>
<?php
require 'functions.php';
$bdd = new PDO('mysql:host=localhost;dbname=banque;charset=utf8', 'root', '');

// on insére des clients dans la base de données, 15 clients à la fois!
for($i=0; $i<2; $i++){

// on génére un 'sel' pour chaque mot de passe aléatoire, puis on le hash
$salt = random_bytes(16);
$password = generateRandomPassword($lenghtMdp);
echo $password;
echo '<br>';
$hashedPassword = password_hash($password.$salt, PASSWORD_BCRYPT);
// on généres des noms/prénoms aléatoirement!
$myName = generateRandomElement($names);
$myFirstName = generateRandomElement($firstName);
// reqêtes pour insérer des clients!
$insertreq = $bdd->prepare('INSERT INTO clients (nom, prénom, numéro, Email, identifiant, mdp, date_creation_compte, type_compte, sel,date_naissance) VALUES (:nom, :prenom, :numero, :email, :id, :mdp, :date_r, :typee, :sel, :date_n)');
$insertreq->execute(["nom" => $myName, "prenom" => $myFirstName, "numero" => generatePhoneNumber(), "email" => generateEmail(strtolower($myName.'.'.$myFirstName)), "id" => generateRandomId($lenghtId), "mdp" => $hashedPassword, "date_r"=> generateDate(1), "typee"=> 'particulier', "sel"=> $salt, "date_n"=>generateDate(0)]);
}
?>
  </body>
</html>

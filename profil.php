<?php
session_start();
require 'global/header.php';
//si pas connecté alors on pars autoatiquement à la page de connexion
if (!isset($_SESSION['id_client'])){header('Location:connexion.php');}
//dans le cas d'un administrateur alors l'id utilisé sera obtenu via get si l'admin veut voir le profil d'un client, sinon via session pour son propre profil 
if($_SESSION['role']=='administrateur'){
    $a=1;
    $_SESSION['id_utilise']=$_SESSION['id_client'];
    if(isset($_GET['id'])){
        $a=2;
        $_SESSION["id_utilise"]=$_GET["id"];
 }
}
//dans le cas d'un utilsateur l'id utilisé est celui de la session
else{
    $_SESSION['id_utilise']=$_SESSION['id_client'];
    $a=0;
}

$n=0;
$s=0;
?>
<title>Bouh</title>
</head>           
<body>

<!-- Barre de navigation-->
<nav>
    <ul>
        <li><a href="premiere.php">Accueil</a></li>
        <?php if(!$a){?>
        <li><a href="offres.php">Nos offres</a>
        <ul>
                <li><a href="#">Jeunes de -26ans</a></li>
                <li><a href="#">Assurances vie</a></li>
                <li><a href="#">Prêts</a></li>
            </ul>
        <?php
        }
        else {?>
            <li><a href="liste_clients.php">Liste des clients</a>
        <?php
        }
        ?>    
        <li><a href="propos.php">À propos</a></li>
        <li><a href="#">Contact</a></li>
        <?php
        //si un cookie est set, contenant a derniére connexion de l'utilisateur, alors on l'affiche
        if(isset($_COOKIE["derniere_co".$_SESSION["id_client"]])){?>
            <li>Derniére connexion : <?=$_COOKIE["derniere_co".$_SESSION["id_client"]];?></li>
        <?php }
        // On met une photo par défaut si pas de cookie contenant la photo mise par l'utilisateur enregistré
        if(!isset($_COOKIE["profile_image".$_SESSION['id_client']])) {?>
            <img class="profile-picture" src="images/defaut.png" alt="defaut.png">
        <?php }
        else { ?>
            <img class="profile-picture" src="<?= $_COOKIE["profile_image".$_SESSION['id_client']]?>" alt="<?="profile_image".$_SESSION['id_client']?>"> 
        <?php }
        ?>
        <!--Profile de l'utilisateur tout à droite de la barre de navigation -->
         <div class ="user-profile">
            <li ><a href="comptes.php"><?=$_SESSION['nom'].'<br>'.$_SESSION['prenom']?></a>
                <ul>
                    <li><a href="profil.php">Profil</a></li>
                    <li><a href="global/deco.php">Se déconnecter</a></li>
                    <li><a href="changementmdp.php?id=<?=$_SESSION['id_client']?>">Changer mdp</a></li>
                </ul> 
            </li> 
        </div>   
    </ul>       
</nav>

<?php
//si le formulaire est envoyé, alors on modifie le client 
if (isset($_POST['send'])){
    $s=1;
    $dos ="images/profils/";
    if(verif_age($_POST['date_naissance'])){
        $n=1;
        //création/mise à jour du cookie stockant la photo de profil
        if (isset($_FILES["im"])) {
            $fichier = basename("profile_image".$_SESSION['id_utilise'].".png");
            if (move_uploaded_file($_FILES['im']['tmp_name'], $dos.$fichier)) {
                    setcookie("profile_image".$_SESSION['id_utilise'], $dos.$fichier,time()+30*24*3600);
                }
            }
        $client = $bdd->prepare("UPDATE clients SET nom=?, prénom=?, numéro=?, date_naissance=?, Email=? WHERE id_client = ?");
        $client->execute([$_POST['nom'], $_POST['prenom'], $_POST['numero'], $_POST['date_naissance'], $_POST['mail'], $_SESSION['id_utilise']]);
        if($a!=2){
            $_SESSION['nom'] =  $_POST['nom'];
            $_SESSION['prenom'] = $_POST['prenom'];
        }
        header('Location:premiere.php');
        }
    }

//on récupére les infos du client(le client ne peut changer son identifiant, seul un administrateur de la banque peut le faire 
$infosreq = $bdd->prepare('SELECT nom, prénom, numéro, Email, date_naissance, identifiant FROM clients WHERE id_client = :id');
$infosreq->execute(['id'=>$_SESSION['id_utilise']]);
$infos = $infosreq->fetch();
?>
<!--Formulaire de changement de mise à jour des informations du profil (déjà préremplis avec les infos du client)-->
<div class="page-container">
    <div  class='container'>
        <form  action="" method="POST" enctype="multipart/form-data" class="oui">
            <?php
            //si c'est un administrateur àlors on précise quel profil il modifie
            if($_SESSION['role']=='administrateur' && isset($_GET['id'])){
                ?>
                <h2><?='Modification du profil de '.$infos['prénom'].' '.$infos['nom'];?></h2>
            <?php
            }
            else {
            ?>
                <h2>Modification du profil</h2>
            <?php
            } 
            if(!$n && $s){
                ?>
                <div class="myerr">Vous devez avoir plus de 18 ans!</div>
            <?php
            }
            ?>
            <div class="imput-container">
            <label for="">Nom</label>
                <input type="text" name="nom" value="<?= $infos['nom'] ?>" required>
            </div>
            <div class ="input-container">
            <label for="">Prénom</label>
                <input type="text" name="prenom"  value="<?= $infos['prénom'] ?>" required>
            </div>
            <label for="">Date de naissance</label>
            <div class ="input-container">
                <input type="date" name="date_naissance"  required value="<?= $infos['date_naissance'] ?>">
            </div>
            <label for="">Email</label>
            <div class="input-container">
                    <input type="text" name="mail" required title="Veuillez entrer une adresse mail valide exemple: example@mail.fr" value="<?= $infos['Email'] ?>">
                </div>
            <label for="">Téléphone</label>
            <div class="input-container">
                <input type ="tel" name="numero" pattern='(?:\+33|0)[1-9](?:[0-9]{8})' title='Seuls les numéros français sont acceptés.'  value="<?= $infos['numéro'] ?>" required >
            </div>
            <?php 
            if($a){
                ?>
                 <label for="">Identifiant</label>
                 <div class="input-container">
                    <input type ="text" name="identifiant" pattern='[0-9]{10}' title='Veuillez rentrer un identifiant à dix chiffres' value="<?= $infos['identifiant'] ?>" required >
                </div>
            <?php
            }
            ?>
            <div class="input-container">
                <label for="">Photo de profil</label>
                <input type="file" accept="image/png, image/jpeg, image/jpg" title="Formats acceptés : png, jpeg, jpg" name="im"> 
            </div>    
            <button type="submit" name="send">Mettre à jour</button>
        </form>
    </div>
</div>
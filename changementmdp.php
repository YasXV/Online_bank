<?php
//sftp ou ssl pour l'outil ftp
session_start();
require 'global/header.php';
$identique = 0;
//si nous n'avons pas demandé à changer de mot de passe alors on retourne à la page de connexion
if(!isset($_SESSION["nouveauInscrit"]) && $_SESSION["nouveauInscrit"]!=2 && !isset($_GET["id"])){header('Location:connexion.php');}
//dans le cas où nous somme venus par get dans cette page (donc un client connecté veut changer son mot de passe)
if(isset($_GET["id"])){
    $_SESSION['nouveauInscrit']=0;
    $_SESSION["idNew"]=$_GET["id"];
}
?>
<title>Bouh</title>
</head>           
<body>
<!--barre de navigation-->
<nav>
    <ul>
        <li><b>Bouh</b></li>
        <li><a href="premiere.php">Acceuil</a>
    </ul>       
</nav>
<?php 
//on vérifie que les 2 nouveaux mdp rentrés sont identiques!
if (isset($_POST['send'])){
    if($_POST['mdp1']==$_POST["mdp2"]){
        echo $_POST['mdp1'];
        echo $_POST["mdp2"];
        echo $_POST['mdp1']==$_POST["mdp2"];
        $identique=2;
        //prise en compte du nouveau mot de passe, hashage et ajout dans la base de données
        $salt=random_bytes(16);
        $hashedPassword=password_hash($_POST["mdp1"].$salt, PASSWORD_BCRYPT);
        //update du nouveau mot de passe et sel ! 
        $insertreq = $bdd->prepare('UPDATE clients
        SET 
        mdp = :new_mdp,
        sel = :salt,
        actif = :actiff
        WHERE identifiant = :id');
        $insertreq->execute(['new_mdp'=>$hashedPassword, "salt" => $salt, "actiff"=>1, "id" => $_SESSION['idNew']]);
        $_SESSION['nouveauInscrit']=0;
    }
    else{
        $identique=1;
    }
}?>
<!--Formulaire de changement de mot de passe-->
<div class="page-container">
    <div class="container">
        <?php 
            if($identique==1){
                ?>
                <div class="myerr">Les mots de passes doivent êtres identiques!</div>
            <?php
            }
            else{
                header("Location:connexion.php");
            }?>
            <form  action="" method="POST">
                <h2>Changement de mot de passe</h2>
                <input type="text" name="id" pattern = "^<?=$_SESSION['idNew']; ?>$" title="Vous devez renseigner l'identifiant affecté" placeholder="Identifiant" required>
                <?php if(isset($_SESSION['mdpNew'])){?>
                    <input type="password" name="mdpold" pattern= "^<?=$_SESSION['mdpNew']; ?>$" title="Vous devez renseigner l'ancien mot de passe affecté" placeholder= "Ancien mot de passe" required>
                <?php
                }
                else {?>
                    <input type="password" name="mdpold"  title="Vous devez renseigner l'ancien mot de passe affecté" placeholder= "Ancien mot de passe" required>
                <?php
                }
                ?>
                <input type="password" name="mdp1" placeholder="Nouveau mot de passe" required>
                <input type="password" name="mdp2" placeholder="Vérification du mot de passe" required>
                <button type="submit" name="send">Changer le mot de passe</button>
            </form>
    </div>
</div>

<?php require 'global/footer.php'; ?>
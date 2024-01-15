<?php
session_start();
require 'global/header.php';
//on vérifie que nous avons affaire à un administrateur ou non
$i=0;
if($_SESSION['role']=='administrateur' || !isset($_GET['id'])){header("Location:premiere.php");
}
else {
    $a=0;
}
//si pas connecté alors on pars autoatiquement à la page de connexion
if (!isset($_SESSION['id_client'])){header('Location:connexion.php');}
?>
<title>Bouh</title>
</head>           
<body>

<!-- Barre de navigation-->
<nav>
    <ul>
        <li><a href="premiere.php">Accueil</a></li>
        <?php if(!$a){?>
        <li><a href="virement.php">Faire un virement</a>
        <li><a href="ajout_benef.php">Ajouter un bénéficiaire</a></li>
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
        </li>
        <li><a href="#">À propos</a></li>
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
                    <li><a href="profil.php">Changer mdp</a></li>
                    <li><a href="global/deco.php">Se déconnecter</a></li>   
                </ul> 
            </li> 
        </div>
    </ul>       
</nav>
<?php 
if(isset($_POST["sendC"])){
    //Vérification du client
    $connexionReq = $bdd->prepare('SELECT sel, mdp, id_client, actif, identifiant FROM clients WHERE identifiant = :id');
    $connexion = $connexionReq->execute(['id'=>$_POST['identifiant']]);
    $client = $connexionReq->fetch();

    //verication de l'existence du client, du mdp, de l'id et si le compte est actif!si oui insertion du nouveau compte dans compte, ainsi que l'association du client/compte dans association
    if($client && password_verify($_POST['mdp'].$client['sel'], $client['mdp']) && $client['actif']){
        $insertreq = $bdd->prepare('INSERT INTO comptes (num_compte, solde, type_compte, date_ouverture, statut_compte, IBAN) VALUES (:num_comptee, :soldee, :type_comptee, :date_ouverturee, :statut_comptee, :IBANe)');
        $insertreq->execute([
            "num_comptee"=>generateRandomId(6),
            "soldee"=>0,
            "type_comptee"=>$_POST["type"],
            "date_ouverturee"=>date("Y-m-d"),
            "statut_comptee"=>"ouvert",
            "IBANe"=>"FR".generateRandomId(26)]
        );
        $numCompte = $bdd->query('SELECT COUNT(*) FROM comptes')->fetchColumn();
        //insertion dans association
        $insertasso = $bdd->prepare('INSERT INTO association (id_compte, id_client) VALUES (:id_comptee, :id_cliente)');
        $insertasso->execute(["id_comptee"=>$numCompte, "id_cliente"=>$_SESSION["id_client"]]);
        header("Location:premiere.php");
    }
    else{
        $i=1;
    }
}
?>
<!-- Message de bienvenue-->
<h1>Bienvenue <?= $_SESSION['prenom'].' '.$_SESSION['nom']?>. Nous sommes le <?=date('d-m-Y')?>.</h1>
<div class="page-container">
    <div class="container">
            <form  action="" method="POST">
                <h2>Ajout d'un compte</h2>
                <?php 
                if($i){
                    ?>
                    <div class="myerr">Identité non verifiée!</div>
                    <?php
                }
                ?>
                <div>
                    <select name="type" id="compte">
                        <option value="courant">courant</option>
                        <option value="Livret A">Livret A</option>
                        <option value="épargne">Epargne</option>
                    </select>
                </div>
                <input type="text" name=identifiant placeholder="identifiant" required>        
                <input type="password" name="mdp" placeholder="Mot de passe" required>
                <button type="submit" name="sendC">Ajouter</button>
            </form>           
    </div>
</div>
<?php
// on ferme la page
require 'global/footer.php';
?>
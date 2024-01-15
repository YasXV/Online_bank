<?php
session_start();
require 'global/header.php';
if (!isset($_SESSION['id_client'])){header('Location:connexion.php');}
?>
<title>Bouh - Cartes</title>
</head>           
<body>

<!--Barre de navigation-->
<nav>
    <ul>
        <li><a href="premiere.php">Accueil</a></li>
        <li><a href="offres.php">Nos offres</a>

            <ul>
                <li><a href="#">Jeunes de -26ans</a></li>
                <li><a href="#">Assurances vie</a></li>
                <li><a href="#">Prêts</a></li>
            </ul>
        </li>
        <li><a href="propos.php">À propos</a></li>
        <li><a href="#">Contact</a></li>
        <?php
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
// on ferme la page
require 'global/footer.php';
?>
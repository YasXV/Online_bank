<?php
session_start();
require 'global/header.php';
//on vérifie que nous avons affaire à un administrateur ou non
if($_SESSION['role']=='administrateur'){$a=1;
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
        <li><a href="comptes.php">Cartes</a></li>
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
                    <li><a href="profil.php">Changer mdp</a></li>
                    <li><a href="global/deco.php">Se déconnecter</a></li>   
                </ul> 
            </li> 
        </div>
    </ul>       
</nav>
<!-- Message de bienvenue-->
<h1>Bienvenue <?= $_SESSION['prenom'].' '.$_SESSION['nom']?>. Nous sommes le <?=date('d-m-Y')?>.</h1>
<?php

if($_SESSION['role']!='administrateur'){?>
    <!-- Affichage des comptes + possibilité d'ouvrir un autre compte, si pas de compte affichage d'un message + possibilité d'ouvrir compte-->
    <?php
    $insertreq = $bdd->prepare('SELECT * from comptes 
                                LEFT JOIN association ON comptes.id_compte = association.id_compte
                                WHERE association.id_client = :id_client');
    $insertreq->execute(["id_client" => $_SESSION['id_client']]); 
    $comptesAll = $insertreq->fetchAll();
    $nb_comptes = sizeof($comptesAll);
    if(($nb_comptes==0)){?>
          <div class="page-container">
          <div  class='container'>
            <form  action="ajout_compte.php?id=<?=$_SESSION['id_client']?>" method="POST" class="oui">
                <h2>Vous n'avez pas (encore) de compte chez nous!</h2> 
                <center> 
                <button type="submit" name="ajout">Créer un compte</button>
                </center>
            </form>
          </div>
      </div>
      <?php
    }
    else {
      ?>
      <div class="page-container">
          <div class="container">
              <div class="row">
                  <?php
                  foreach ($comptesAll as $el) {
                      ?>
                      <div class="col-md-<?=$nb_comptes*3?>"> 
                      <div class="account-info border p-3">
                            <center>
                              <div class="border-bottom mb-2 pb-2">
                              <?php 
                              echo '<h1>Numéro du compte : '.$el['num_compte'].'<br></h1>';
                              ?>
                              </div>
                              <?php
                              echo '<u><h2>Solde</u>: '.$el['solde'].'€<br></h2>';
                              echo '<u><h2>Statut du compte</u>: '.$el['statut_compte'].'<br></h2>';
                              echo '<u><h2>Type du compte</u>: '.$el['type_compte'] .'<br></h2>';
                              ?>
                              </center>
                          </div>
                      </div>
                      <?php
                  }
                  ?>
                  <a class="alien" href="ajout_compte.php?id=<?=$_SESSION['id_client']?>"">Ajouter un compte ?</a>
              </div>
          </div>
      </div>
      <?php
    }
}
// on ferme la page
require 'global/footer.php';
?>
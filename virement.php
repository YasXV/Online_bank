<?php
//sftp ou ssl pour l'outil ftp
session_start();
require 'global/header.php';
//on renvoie vers la page de connexion si on n'est pas connecté! 
if (!isset($_SESSION['id_client'])){header('Location:connexion.php');}
?>
<title>Bouh - Virements</title>
</head>           
<body>

<!-- Barre de navigation-->
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
$insertreq = $bdd->prepare('SELECT * from beneficiaires_internes 
                            LEFT JOIN association ON beneficiaires_internes.id_client = association.id_client
                            WHERE association.id_client = :id_client');
$insertreq->execute(["id_client" => $_SESSION['id_client']]); 
$intern= $insertreq->fetchAll();
$insertreqex = $bdd->prepare('SELECT * from beneficiaires_externes 
                            LEFT JOIN association ON beneficiaires_externes.id_client = association.id_client
                            WHERE association.id_client = :id_client');
$insertreqex->execute(["id_client" => $_SESSION['id_client']]); 
$extern= $insertreqex->fetchAll();
print_r($extern);echo '<br>';
print_r($intern); 
$benef = array_merge($extern, $intern);
$nb_benef = sizeof($benef);
if($benef==0){ ?>
  <div class="page-container">
    <div  class='container'>
       <h1>Vous n'avez pas de bénéficiaires! </h1>
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
              foreach ($benef as $el) {
                  ?>
                  <div class="col-md-<?=$nb_benef*3?>"> 
                  <div class="account-info border p-3">
                        <center>
                          <div class="border-bottom mb-2 pb-2">
                          <?php 
                          echo '<h1> IBAN : '.$el['IBAN'].'<br></h1>';
                          ?>
                          </div>
                          </center>
                      </div>
                  </div>
                  <?php
              }
              ?>
          </div>
      </div>
  </div>
  <?php
}
?>
<?php require 'global/footer.php'; ?>
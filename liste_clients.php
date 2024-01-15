<?php
session_start();
require 'global/header.php';
//on vérifie que nous avons affaire à un administrateur ou non et que nous sommes connectés
if(!isset($_SESSION['id_client']) && $_SESSION['role']!='administrateur'){header('Location:connexion.php');}
?>
<title>Bouh - Liste des clients</title>
</head>           
<body>

<!-- Barre de navigation-->
<nav>
    <ul>
        <li><a href="premiere.php">Accueil</a></li>
        <li><a href="liste_clients.php">Liste des clients</a>           
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

<!-- Message de bienvenue-->
<h1>Bienvenue <?= $_SESSION['prenom'].' '.$_SESSION['nom']?>. Nous sommes le <?=date('d-m-Y')?>.</h1>
<?php
//on récupére la liste de tout les clients non admin dans notre bdd via une requête
$insertreq = $bdd->query('SELECT * from clients WHERE type_compte !="administrateur" AND actif = 1 ORDER BY nom ASC'); 
$clientsAll = $insertreq->fetchAll();
//suppression d'un client(actif prend la valeur 0) dans le cas du clic sur le bouton "suprrimer client" 
if(isset($_GET['idSupp'])){
    $client = $bdd->prepare("UPDATE clients SET actif=? WHERE id_client = ?");
    $client->execute([0,$_GET['idSupp']]);
    header("Location:liste_clients.php");
}
?>
<div class="">
    <!-- tableau de tout les clients. Lors d'un clic sur l'un des clients vous êtes redirigé vers son profil. -->
    <h2>Liste des clients</h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Type de compte</th>
            <th>Identifiant</th>
            <th>Date de création de compte</th>
            <th>Supression</th>
            <th>Modification</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($clientsAll as $clientItem){?>
            <tr>
                <td><?=$clientItem['prénom']?></td>
                <td><?=$clientItem['nom']?></td>
                <td><?=$clientItem['Email']?></td>
                <td><?=$clientItem['type_compte']?></td>
                <td><?=$clientItem['identifiant']?></td>
                <td><?=$clientItem['date_creation_compte']?></td>
                <td>
                    <form action="liste_clients.php?idSupp=<?=$clientItem['id_client']?>" method="POST">
                    <button type="submit" name="supp">Supprimer client</button>
                    </form>
                </td>
                <td><a class="alien" href="profil.php?id=<?=$clientItem['id_client']?>">Profil</a></td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
</div>
<?php
// on ferme la page
require 'global/footer.php';
?>
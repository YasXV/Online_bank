<?php
session_start();

if (isset($_SESSION['nouveauInscrit'])) {
    if ($_SESSION['nouveauInscrit'] == 1) {
        // SFTP ou SSL pour l'outil FTP
        require 'global/header.php';
?>
<title>Bouh - Bienvenue</title>
<link rel="stylesheet" type="text/css" href="css/style_body_firstIns.css">
</head>
<body>

<!--barre de navigation-->
<nav>
    <ul>
        <b>Bouh</b>
    </ul>       
</nav>

<!-- Attention, si on actualise la page, on perd tout, pour plus de sécurité, donc il faut noter l'identifiant et le mot de passe avant d'actualiser-->
<div class="page-container">
    <div class="container">
        <form  action="changementmdp.php" method="POST">
            <h2>Bienvenue! Nous sommes ravies de vous compter parmis nous!</h2>
            <div class="input-container">
                Voici votre identifiant : <strong> <?=$_SESSION['idNew']?></strong>
            </div>
            <div class="input-container">
                Voici votre mot de passe : <strong>  <?=$_SESSION['mdpNew']?> </strong>
            </div>
            <button type="submit" name="sendFirst">Changer le mot de passe</button>
        </form>           
    </div>
</div>
<?php
        $_SESSION['nouveauInscrit'] = 2;
    } else {
        header("Location:connexion.php");
    }
} else {
    header("Location:connexion.php");
}
require 'global/footer.php';
?>
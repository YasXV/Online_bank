<?php
//sftp ou ssl pour l'outil ftp
session_start();
require 'global/header.php';
?>
</head>           
<body>
<?php
$estPasClient=0;
// utilisateurs tests -> (mdp : $S$sq | id : 5549676412) et (mdp : w#JAv | id : 7625651557)
if(isset($_POST["send"])){
        //Vérification du client/admin!
        $connexionReq = $bdd->prepare('SELECT sel, mdp, nom, prénom, id_client, actif, identifiant, type_compte FROM clients WHERE identifiant = :id');
        $connexion = $connexionReq->execute(['id'=>$_POST['id']]);
        $client = $connexionReq->fetch();
        
        //verication de l'existence du client, du mdp, de l'id et si le compte est actif!
        if($client && password_verify($_POST['mdp'].$client['sel'], $client['mdp']) && $client['actif']){
            $_SESSION['nom'] =  $client['nom'];
            $_SESSION['prenom'] = $client['prénom'];
            $_SESSION['id_client'] = $client['id_client'];
            $_SESSION['identifiant'] = $client['identifiant'];
            $_SESSION['role'] = $client['type_compte'];
            header('Location: premiere.php');
        }
        else{
            $estPasClient=1;
        }
    }

?>
<nav>
    <ul>
        <b>Bouh</b>
    </ul>       
</nav>
<div class="page-container">
    <div class="container">
            <form  action="" method="POST">
                <h2>Connexion</h2>
                <?php 
                if($estPasClient){
                    ?>
                    <div class="myerr">Mot de passe ou identifiant incorrect!</div>
                    <?php
                }
                ?>
                <input type="text" name="id" placeholder="Identifiant" required>
                <input type="password" name="mdp" placeholder="Mot de passe" required>
                <button type="submit" name="send">Se connecter</button>
                <a class="alien" href="inscription.php">S'inscrire</a>
            </form>           
    </div>
</div>
<?php require 'global/footer.php'; ?>
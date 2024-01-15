<?php
//sftp ou ssl pour l'outil ftp
session_start();
require 'global/header.php';
?>
<title>Bouh - Inscription</title>
</head>           
<body>
<?php
$n=0;
$s=0;
if (isset($_POST['send'])){
    $s=1;
    if(verif_age($_POST['date_naissance'])){
        $n=1;
        //génération aléatoire d'un mdp et d'un Id pour le nouveau client, les nouvelles valeurs stockés dans une varibale de session!
        $_SESSION['idNew']=generateRandomId($lenghtId);
        $_SESSION['mdpNew']=generateRandomPassword($lenghtMdp);
        $_SESSION['nouveauInscrit']=1;
        $dateToday = date("Y-m-d");
        $salt=random_bytes(16);
        $hashedPassword=password_hash($_SESSION['mdpNew'].$salt, PASSWORD_BCRYPT);
        
        //insertion du nouveau client, actif = 0 par défaut, le client ne deviendra actif que lors du changement de mot de passe 
        $insertreq = $bdd->prepare('INSERT INTO clients (nom, prénom, numéro, Email, identifiant, mdp, date_creation_compte, type_compte, sel,date_naissance, actif) VALUES (:nom, :prenom, :numero, :email, :id, :mdp, :date_r, :typee, :sel, :date_n, :actif)');
        $insertreq->execute(["nom" => $_POST['nom'], "prenom" => $_POST['prenom'], "numero" => $_POST['numero'], "email" => $_POST['mail'], "id" => $_SESSION['idNew'], "mdp" => $hashedPassword, "date_r"=> $dateToday,"typee"=> $_POST['type'], "sel"=> $salt, "date_n"=>$_POST['date_naissance'],"actif" => 0]);
        header("Location:nouveauInscrit.php");
    }
}
?>

<!--barre de navigation-->
<nav>
    <ul>
        <b>Bouh</b>
    </ul>       
</nav>

<div class="page-container">
    <div  class='container'>
    <form  action="" method="POST" class="oui">
        Déjà inscrit ? <a class="alien" href="connexion.php">Se connecter</a>
            <?php 
            if(!$n && $s){
                ?>
                <div class="myerr">Vous devez avoir plus de 18 ans!</div>
            <?php
            }
            ?>
                <h2>Inscription</h2>
                <div class ="input-container">
                    <input type="text" name="nom" placeholder="Nom" required>
                </div>
                <div class="input-container">
                    <input type="text" name="prenom" placeholder="Prenom" required>
                </div>
                <div class="input-container">
                    <input type="text" name="mail" placeholder="Adresse email" required title="Veuillez entrer une adresse mail valide exemple: example@mail.fr" >
                </div>
                <input type ="date" name="date_naissance" required>
                <div class="input-container">
                    <input type ="tel" name="numero" pattern='(?:\+33|0)[1-9](?:[0-9]{8})' title='Seuls les numéros français sont acceptés.' required placeholder="numéro de téléphone">
                </div>
                <div>
                    <select name="type" id="compte">
                        <option value="particulier">particulier</option>
                        <option value="professionnel">professionnel</option>
                    </select>
                </div>            
                <button type="submit" name="send">S'inscrire</button>
            </form>
    </div>
</div>

<?php require 'global/footer.php'; ?>
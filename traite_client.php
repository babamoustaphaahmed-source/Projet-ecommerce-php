<?php 
  include "../Html/connection.php";

if ($_SERVER['REQUEST_METHOD'] =='POST'){
    $nom= $_POST["nom"];
    $prenom = $_POST["prenom"];
    $tel = $_POST["tel"];
    $adresse = $_POST["adresse"];
    $identifiant = $_POST["identifiant"];
    $modepasse= $_POST["modepasse"];
     
    $SQL = "INSERT INTO clients1 (nomcl, prenom,tel ,adre,identifiant,modepasse) VALUES (:nom, :prenom,:tel,:adresse,:identifiant,:modepasse)";
    $Stmt = $pdo->prepare($SQL);
    $Stmt->bindparam('nom', $nom);
    $Stmt->bindparam('prenom', $prenom);
    $Stmt->bindparam('tel', $tel);
    $Stmt->bindparam('adresse', $adresse);
    $Stmt->bindparam('identifiant', $identifiant);
    $Stmt->bindparam('modepasse', $modepasse);
    
    if ($Stmt->execute()){
        echo "Mr / Mme" . $nom  ."Votre enregistrement à été effectuer avec succèss";
    }else{
        echo "Echec d'enregistrement du client",$nom;
    }
}





?>
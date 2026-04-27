<?php
$serveur = "localhost";
$utilisateur ="root";
$mdp ="";
$bdd ="site_vente";

$conn = new mysqli($serveur, $utilisateur, $mdp, $bdd);

if ($conn->connect_error){
      die("Connexion échouer: " . $conn->connect_error);
}



?>
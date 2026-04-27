<?php
session_start();
include "connexion_base.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header('Location: login.php');
    exit();
}

if(isset($_POST['id_commande']) && isset($_POST['statut'])){
    $id_commande = $_POST['id_commande'];
    $statut = $_POST['statut'];
    
    $SQL = "UPDATE commande SET statut = ? WHERE id_commande = ?";
    $stmt = $conn->prepare($SQL);
    $stmt->bind_param("si", $statut, $id_commande);
    $stmt->execute();
}

header('Location: admin_commandes.php');
exit();
?>
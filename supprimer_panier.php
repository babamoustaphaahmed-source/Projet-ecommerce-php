<?php
session_start();
include "connexion_base.php";

if (!isset($_SESSION['id']) || !isset($_GET['id'])){
    header('Location: panier.php');
    exit();
}

$id_panier = $_GET['id'];
$id_user = $_SESSION['id'];

$SQL = "DELETE FROM panier WHERE id_panier = ? AND id_utilisateur = ?";
$stmt = $conn->prepare($SQL);
$stmt->bind_param("ii", $id_panier, $id_user);
$stmt->execute();

header('Location: panier.php');
exit();
?>
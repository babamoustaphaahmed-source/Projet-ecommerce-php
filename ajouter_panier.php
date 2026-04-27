<?php
session_start();
include "connexion_base.php";
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'client'){ header('Location: login.php'); exit(); }
if(isset($_GET['id'])){
    $id_article = $_GET['id'];
    $id_user = $_SESSION['id'];
    $SQL_check = "SELECT * FROM panier WHERE id_utilisateur = ? AND id_article = ?";
    $stmt_check = $conn->prepare($SQL_check);
    $stmt_check->bind_param("ii", $id_user, $id_article);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    if($result->num_rows > 0){
        $SQL = "UPDATE panier SET quantite = quantite + 1 WHERE id_utilisateur = ? AND id_article = ?";
    } else {
        $SQL = "INSERT INTO panier (id_utilisateur, id_article, quantite) VALUES (?, ?, 1)";
    }
    $stmt = $conn->prepare($SQL);
    $stmt->bind_param("ii", $id_user, $id_article);
    $stmt->execute();
}
header('Location: dashboard_client.php?added=1');
exit();
?>
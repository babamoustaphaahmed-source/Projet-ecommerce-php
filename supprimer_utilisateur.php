<?php
session_start();
include "connexion_base.php";

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])){
    header("Location: liste_utilisateur.php");
    exit;
}

$id = $_GET['id'];

// Sécurité : tu peux pas te supprimer toi-même
if($id == $_SESSION['id']){
    die("Tu peux pas te bannir toi-même baba 😂");
}

// ÉTAPE 1 : On supprime son panier
$stmt = $conn->prepare("DELETE FROM panier WHERE id_utilisateur = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// ÉTAPE 2 : On supprime ses commandes - ici c'est id_client
$stmt = $conn->prepare("DELETE FROM commande WHERE id_client = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// ÉTAPE 3 : On supprime le compte
$stmt = $conn->prepare("DELETE FROM utilisateur WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: liste_utilisateur.php");
exit;
?>
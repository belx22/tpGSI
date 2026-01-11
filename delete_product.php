<?php
include __DIR__ . '/../config/dbcon.php';

if (!isset($_POST['id'])) {
    header('Location: products.php');
    exit;
}

$id = $_POST['id'];

// Récupérer l'image
$stmt = $pdo->prepare("SELECT image FROM produits WHERE id=?");
$stmt->execute([$id]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

// Supprimer l'image si elle existe
if ($produit && $produit['image'] && file_exists(__DIR__ . '/../' . ltrim($produit['image'], '/'))) {
    unlink(__DIR__ . '/../' . ltrim($produit['image'], '/'));
}

// Supprimer le produit
$stmt = $pdo->prepare("DELETE FROM produits WHERE id=?");
$stmt->execute([$id]);

header('Location: products.php');
exit;

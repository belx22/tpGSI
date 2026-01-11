<?php
include __DIR__ . '/../config/dbcon.php';
$pageTitle = "Ajouter une catégorie";
include __DIR__ . '/../includes/header.php';

if (isset($_POST['ajouter'])) {
    $nom = $_POST['nom'];
    $stmt = $pdo->prepare("INSERT INTO categories (nom) VALUES (?)");
    $stmt->execute([$nom]);
    echo "<div class='alert alert-success'>Catégorie ajoutée avec succès !</div>";
}
?>

<h3 class="text-brown mb-4">Ajouter une catégorie</h3>
<form method="POST" class="bg-white p-4 rounded shadow">
    <div class="mb-3">
        <label>Nom de la catégorie</label>
        <input type="text" name="nom" class="form-control" required>
    </div>
    <button name="ajouter" class="btn btn-success">Ajouter</button>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>

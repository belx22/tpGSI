<?php
include __DIR__ . '/../config/dbcon.php';
$pageTitle = "Modifier une catégorie";
include __DIR__ . '/../includes/header.php';

if (!isset($_GET['id'])) {
    header('Location: categories.php');
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id=?");
$stmt->execute([$id]);
$categorie = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$categorie) {
    echo "<div class='alert alert-danger'>Catégorie non trouvée.</div>";
    include __DIR__ . '/../includes/footer.php';
    exit;
}

if (isset($_POST['modifier'])) {
    $nom = $_POST['nom'];
    $stmt = $pdo->prepare("UPDATE categories SET nom=? WHERE id=?");
    $stmt->execute([$nom, $id]);
    echo "<div class='alert alert-success'>Catégorie modifiée avec succès !</div>";
}
?>

<h3 class="text-brown mb-4">Modifier la catégorie</h3>
<form method="POST" class="bg-white p-4 rounded shadow">
    <div class="mb-3">
        <label>Nom de la catégorie</label>
        <input type="text" name="nom" class="form-control" required value="<?= htmlspecialchars($categorie['nom']) ?>">
    </div>
    <button name="modifier" class="btn btn-warning text-white">Modifier</button>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>

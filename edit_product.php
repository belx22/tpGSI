\<?php
include __DIR__ . '/../config/dbcon.php';
$pageTitle = "Modifier un produit";
include __DIR__ . '/../includes/header.php';

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>Produit non trouvé.</div>";
    include __DIR__ . '/../includes/footer.php';
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    echo "<div class='alert alert-danger'>Produit non trouvé.</div>";
    include __DIR__ . '/../includes/footer.php';
    exit;
}

// Récupérer les catégories
$categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

$errors = [];

if (isset($_POST['modifier'])) {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = trim($_POST['prix']);
    $quantite = intval($_POST['quantite']);
    $categorie_id = intval($_POST['categorie_id']);
    $monnaie = $_POST['monnaie'];

    // Validation
    if (!$nom || !$prix || !$quantite || !$categorie_id || !$monnaie) {
        $errors[] = "Tous les champs obligatoires doivent être remplis.";
    }

    // Prix flexible
    $prix_sanitized = preg_replace('/[^\d.,-]/u', '', $prix);
    $prix_sanitized = str_replace(',', '.', $prix_sanitized);
    if (!is_numeric($prix_sanitized)) {
        $errors[] = "Le prix doit être un nombre valide.";
    } else {
        $prix_sanitized = (float)$prix_sanitized;
    }

    // Gestion de l'image
    $image = $produit['image'];
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . '/../uploads/';
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $uploadFile = $uploadDir . $imageName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            // Supprimer l'ancienne image
            if ($image && file_exists(__DIR__ . '/../' . ltrim($image, '/'))) {
                unlink(__DIR__ . '/../' . ltrim($image, '/'));
            }
            $image = '/SantaLucia/uploads/' . $imageName;
        } else {
            $errors[] = "Erreur lors de l'upload de l'image.";
        }
    }

    // Mise à jour si pas d'erreurs
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE produits SET nom=?, description=?, prix=?, quantite=?, categorie_id=?, image=?, monnaie=? WHERE id=?");
        $stmt->execute([$nom, $description, $prix_sanitized, $quantite, $categorie_id, $image, $monnaie, $id]);
        echo "<div class='alert alert-success'>Produit modifié avec succès !</div>";

        // Recharger les infos après modification
        $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
        $stmt->execute([$id]);
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<h3 class="text-brown mb-4">Modifier le produit</h3>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow">
    <div class="mb-3">
        <label>Nom</label>
        <input type="text" name="nom" class="form-control" required value="<?= htmlspecialchars($produit['nom']) ?>">
    </div>
    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control"><?= htmlspecialchars($produit['description']) ?></textarea>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label>Prix</label>
            <input type="text" name="prix" class="form-control" required value="<?= htmlspecialchars($produit['prix']) ?>">
        </div>
        <div class="col">
            <label>Quantité</label>
            <input type="number" name="quantite" class="form-control" required value="<?= $produit['quantite'] ?>">
        </div>
    </div>
    <div class="mb-3">
        <label>Monnaie</label>
        <select name="monnaie" class="form-select" required>
            <?php 
            $monnaies = ['€', '$', '£', 'CFA', '¥', '₹'];
            foreach ($monnaies as $m): ?>
                <option value="<?= $m ?>" <?= ($produit['monnaie']==$m)?'selected':'' ?>><?= $m ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Catégorie</label>
        <select name="categorie_id" class="form-select" required>
            <option value="">-- Sélectionner --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id']==$produit['categorie_id']?'selected':'' ?>><?= htmlspecialchars($cat['nom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Image</label>
        <input type="file" name="image" class="form-control">
        <?php if ($produit['image']): ?>
            <img src="<?= $produit['image'] ?>" width="80" class="mt-2">
        <?php endif; ?>
    </div>
    <button name="modifier" class="btn btn-warning text-white">Modifier le produit</button>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<?php
include __DIR__ . '/../config/dbcon.php';
$pageTitle = "Ajouter un produit";
include __DIR__ . '/../includes/header.php';

// Récupération des catégories
$categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

// Liste des monnaies disponibles
$monnaies = ['€', '$', '£', 'CFA', '¥', '₹'];

if (isset($_POST['ajouter'])) {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = trim($_POST['prix']);
    $quantite = trim($_POST['quantite']);
    $categorie_id = trim($_POST['categorie_id']);
    $monnaie = trim($_POST['monnaie']);

    $errors = [];

    // Validation des champs
    if (empty($nom)) $errors[] = "Le nom est obligatoire.";
    if (empty($description)) $errors[] = "La description est obligatoire.";
    if (empty($prix)) {
        $errors[] = "Le prix est obligatoire.";
    } else {
        // Supprimer tout sauf chiffres, point et virgule
        $prix_sanitized = preg_replace('/[^\d.,-]/u', '', $prix);
        $prix_sanitized = str_replace(',', '.', $prix_sanitized);

        if (!is_numeric($prix_sanitized)) {
            $errors[] = "Le prix doit être un nombre valide.";
        } else {
            $prix_sanitized = (float)$prix_sanitized;
        }
    }
    if ($quantite === '' || !is_numeric($quantite) || $quantite < 0) $errors[] = "La quantité est obligatoire et doit être un nombre positif.";
    if (empty($categorie_id)) $errors[] = "La catégorie est obligatoire.";
    if (empty($monnaie) || !in_array($monnaie, $monnaies)) $errors[] = "Veuillez sélectionner une monnaie valide.";

    // Gestion de l'image
    if (!isset($_FILES['image']) || empty($_FILES['image']['name'])) {
        $errors[] = "L'image est obligatoire.";
    } else {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $uploadDir = __DIR__ . '/../uploads/';
        $uploadFile = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $image = '/SantaLucia/uploads/' . $imageName;
        } else {
            $errors[] = "Erreur lors de l'upload de l'image.";
        }
    }

    // Insertion en base si pas d'erreurs
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO produits (nom, description, prix, quantite, categorie_id, image, monnaie) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$nom, $description, $prix_sanitized, $quantite, $categorie_id, $image, $monnaie]);
        echo "<div class='alert alert-success mt-3'>Produit ajouté avec succès !</div>";
    } else {
        foreach ($errors as $err) {
            echo "<div class='alert alert-danger mt-2'>{$err}</div>";
        }
    }
}
?>

<h3 class="text-brown mb-4">Ajouter un produit</h3>
<form method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow">
    <div class="mb-3">
        <label>Nom</label>
        <input type="text" name="nom" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control" required></textarea>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label>Prix</label>
            <input type="text" name="prix" class="form-control" required placeholder="Ex: 12.99, 1000, $15, €20">
        </div>
        <div class="col">
            <label>Quantité</label>
            <input type="number" name="quantite" class="form-control" required min="0">
        </div>
    </div>
    <div class="mb-3">
        <label>Catégorie</label>
        <select name="categorie_id" class="form-select" required>
            <option value="">-- Sélectionner --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Monnaie</label>
        <select name="monnaie" class="form-select" required>
            <option value="">-- Sélectionner --</option>
            <?php foreach ($monnaies as $m): ?>
                <option value="<?= $m ?>"><?= $m ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Image</label>
        <input type="file" name="image" class="form-control" required>
    </div>
    <button name="ajouter" class="btn btn-success">Ajouter le produit</button>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<?php
$title = 'Dashboard - Lanka Recipe Book';
require_once 'includes/functions.php';
require_login();
$uid = $_SESSION['user_id']; $msg = ''; $err = '';

// Delete own recipe
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $st = $conn->prepare("DELETE FROM recipes WHERE id=? AND user_id=?");
    $st->bind_param('ii', $id, $uid); $st->execute();
    header('Location: dashboard.php?deleted=1'); exit;
}
// Add recipe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $t = trim($_POST['title'] ?? ''); $c = $_POST['category'] ?? '';
    $i = trim($_POST['ingredients'] ?? ''); $m = trim($_POST['instructions'] ?? '');
    if ($t === '' || $c === '' || $i === '' || $m === '') { $err = 'Please fill in every field.'; }
    else {
        $st = $conn->prepare("INSERT INTO recipes (title, category, ingredients, instructions, user_id) VALUES (?,?,?,?,?)");
        $st->bind_param('ssssi', $t, $c, $i, $m, $uid);
        $st->execute(); $msg = 'Recipe added.';
    }
}
if (isset($_GET['deleted'])) $msg = 'Recipe deleted.';
$mine = $conn->prepare("SELECT * FROM recipes WHERE user_id=? ORDER BY created_at DESC");
$mine->bind_param('i', $uid); $mine->execute(); $mine = $mine->get_result();
require 'includes/header.php';
?>
<main class="container py-5">
  <h1 class="mb-1">Welcome, <?= clean($_SESSION['username']) ?></h1>
  <p class="text-muted">Add a recipe and it appears in the public book straight away.</p>
  <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
  <?php if ($err): ?><div class="alert alert-danger"><?= $err ?></div><?php endif; ?>
  <div class="row g-5">
    <div class="col-lg-6">
      <h2 class="h4">Add a recipe</h2>
      <form method="post" class="needs-validation" novalidate>
        <div class="mb-3"><label class="form-label">Title</label><input name="title" class="form-control" required maxlength="120"><div class="invalid-feedback">Enter a title.</div></div>
        <div class="mb-3"><label class="form-label">Category</label>
          <select name="category" class="form-select" required><option value="">Choose...</option><option>Rice</option><option>Curry</option><option>Snacks</option><option>Sweets</option><option>Drinks</option></select>
          <div class="invalid-feedback">Pick a category.</div></div>
        <div class="mb-3"><label class="form-label">Ingredients (one per line)</label><textarea name="ingredients" rows="4" class="form-control" required minlength="5"></textarea><div class="invalid-feedback">List at least one ingredient.</div></div>
        <div class="mb-3"><label class="form-label">Method (one step per line)</label><textarea name="instructions" rows="4" class="form-control" required minlength="10"></textarea><div class="invalid-feedback">Describe the steps (at least 10 characters).</div></div>
        <button class="btn btn-success">Save recipe</button>
      </form>
    </div>
    <div class="col-lg-6">
      <h2 class="h4">Your recipes</h2>
      <?php if ($mine->num_rows === 0): ?><p class="text-muted">You haven't added any recipes yet. Use the form to add your first one.</p><?php endif; ?>
      <ul class="list-group">
      <?php while ($r = $mine->fetch_assoc()): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span><?= category_icon($r['category']) ?> <?= clean($r['title']) ?></span>
          <a class="btn btn-sm btn-outline-danger" href="?delete=<?= $r['id'] ?>" onclick="return confirm('Delete this recipe?')">Delete</a>
        </li>
      <?php endwhile; ?>
      </ul>
    </div>
  </div>
</main>
<?php require 'includes/footer.php'; ?>

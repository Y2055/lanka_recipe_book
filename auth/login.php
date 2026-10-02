<?php
$base = '../'; $title = 'Log in - Lanka Recipe Book';
require_once '../includes/functions.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['identity'] ?? ''); $p = $_POST['password'] ?? '';
    $st = $conn->prepare("SELECT id, username, password FROM users WHERE username=? OR email=?");
    $st->bind_param('ss', $id, $id); $st->execute();
    $user = $st->get_result()->fetch_assoc();
    if ($user && password_verify($p, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: ../dashboard.php'); exit;
    }
    $err = 'Wrong username/email or password.';
}
require '../includes/header.php';
?>
<main class="container py-5" style="max-width:480px">
  <h1 class="mb-4">Log in</h1>
  <?php if (isset($_GET['registered'])): ?><div class="alert alert-success">Account created. You can log in now.</div><?php endif; ?>
  <?php if ($err): ?><div class="alert alert-danger"><?= $err ?></div><?php endif; ?>
  <form method="post" class="needs-validation" novalidate>
    <div class="mb-3"><label class="form-label">Username or email</label><input name="identity" class="form-control" required><div class="invalid-feedback">Required.</div></div>
    <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required><div class="invalid-feedback">Required.</div></div>
    <button class="btn btn-success w-100">Log in</button>
  </form>
  <p class="mt-3">New here? <a href="register.php">Create an account</a></p>
</main>
<?php require '../includes/footer.php'; ?>

<?php
$base = '../'; $title = 'Sign up - Lanka Recipe Book';
require_once '../includes/functions.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? ''); $e = trim($_POST['email'] ?? ''); $p = $_POST['password'] ?? '';
    if (strlen($u) < 3 || !filter_var($e, FILTER_VALIDATE_EMAIL) || strlen($p) < 6) {
        $err = 'Username needs 3+ characters, email must be valid, password needs 6+ characters.';
    } else {
        $st = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
        $st->bind_param('ss', $u, $e); $st->execute(); $st->store_result();
        if ($st->num_rows > 0) { $err = 'That username or email is already registered.'; }
        else {
            $hash = password_hash($p, PASSWORD_DEFAULT);
            $st = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?,?,?)");
            $st->bind_param('sss', $u, $e, $hash); $st->execute();
            header('Location: login.php?registered=1'); exit;
        }
    }
}
require '../includes/header.php';
?>
<main class="container py-5" style="max-width:480px">
  <h1 class="mb-4">Create your account</h1>
  <?php if ($err): ?><div class="alert alert-danger"><?= $err ?></div><?php endif; ?>
  <form method="post" class="needs-validation" novalidate>
    <div class="mb-3"><label class="form-label">Username</label><input name="username" class="form-control" required minlength="3"><div class="invalid-feedback">At least 3 characters.</div></div>
    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required><div class="invalid-feedback">Enter a valid email address.</div></div>
    <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" id="pw" class="form-control" required minlength="6"><div class="invalid-feedback">At least 6 characters.</div></div>
    <div class="mb-3"><label class="form-label">Confirm password</label><input type="password" id="pw2" class="form-control" required><div class="invalid-feedback">Passwords must match.</div></div>
    <button class="btn btn-success w-100">Sign up</button>
  </form>
  <p class="mt-3">Already have an account? <a href="login.php">Log in</a></p>
</main>
<?php require '../includes/footer.php'; ?>

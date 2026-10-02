<?php
$base = '../';
$title = 'Log In - Lanka Recipe Book';
require_once '../includes/functions.php';

if (is_logged_in()) {
    header('Location: ../dashboard.php');
    exit;
}

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $err = 'Security token invalid. Please refresh the page.';
    } else {
        $id = trim($_POST['identity'] ?? '');
        $p = $_POST['password'] ?? '';

        if ($id === '' || $p === '') {
            $err = 'Please enter your username/email and password.';
        } else {
            $st = $conn->prepare("SELECT id, username, password FROM users WHERE username=? OR email=?");
            $st->bind_param('ss', $id, $id);
            $st->execute();
            $user = $st->get_result()->fetch_assoc();

            if ($user && password_verify($p, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: ../dashboard.php');
                exit;
            } else {
                $err = 'Incorrect username/email or password.';
            }
        }
    }
}

require '../includes/header.php';
?>

<main class="container py-5" style="max-width: 500px;">
  <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">
    <div class="text-center mb-4">
      <div class="brand-logo-badge mx-auto mb-3" style="width: 56px; height: 56px; font-size: 1.8rem;">
        <i class="bi bi-fire"></i>
      </div>
      <h1 class="h3 fw-bold mb-1">Welcome Back</h1>
      <p class="text-muted small">Sign in to publish recipes, save favorites, and explore Ceylon dishes.</p>
    </div>

    <?php if (isset($_GET['registered'])): ?>
      <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div class="small">Account created! You can now log in with your credentials.</div>
      </div>
    <?php endif; ?>

    <?php if ($err): ?>
      <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <div class="small"><?= clean($err) ?></div>
      </div>
    <?php endif; ?>

    <form method="post" class="needs-validation" novalidate>
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

      <div class="mb-3">
        <label class="form-label fw-semibold">Username or Email</label>
        <div class="input-group">
          <span class="input-group-text bg-body-tertiary"><i class="bi bi-person"></i></span>
          <input type="text" name="identity" class="form-control" placeholder="Enter username or email" required value="<?= clean($_POST['identity'] ?? '') ?>">
        </div>
        <div class="invalid-feedback">Required.</div>
      </div>

      <div class="mb-4">
        <label class="form-label fw-semibold">Password</label>
        <div class="input-group">
          <span class="input-group-text bg-body-tertiary"><i class="bi bi-lock"></i></span>
          <input type="password" name="password" id="loginPw" class="form-control" placeholder="Your password" required>
          <button type="button" class="btn btn-outline-secondary btn-toggle-password" data-target="#loginPw" title="Show/Hide Password" aria-label="Toggle password visibility">
            <i class="bi bi-eye"></i>
          </button>
        </div>
        <div class="invalid-feedback">Required.</div>
      </div>

      <button type="submit" class="btn btn-emerald btn-lg w-100 shadow-sm mb-3">
        <span>Log In</span>
        <i class="bi bi-box-arrow-in-right ms-1"></i>
      </button>

      <div class="text-center">
        <span class="text-muted small">New to Lanka Recipe Book?</span>
        <a href="register.php" class="small fw-bold text-emerald ms-1">Create an account</a>
      </div>
    </form>
  </div>
</main>

<?php require '../includes/footer.php'; ?>

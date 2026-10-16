<?php
session_start(); require_once __DIR__.'/../config/database.php';
if(!empty($_SESSION['user'])){header('Location: ../index.php');exit;}
$error=''; $redirect=$_GET['redirect']??'../index.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email=trim($_POST['email']??'');$pass=$_POST['password']??'';
  $stmt=$pdo->prepare("SELECT id,name,email,password FROM users WHERE email=?");$stmt->execute([$email]);$u=$stmt->fetch();
  if($u && password_verify($pass,$u['password'])){
    $_SESSION['user']=['id'=>(int)$u['id'],'name'=>$u['name'],'email'=>$u['email']];
    header('Location: '.($redirect ?: '../index.php'));exit;
  } else $error='Invalid email or password.';
}
$pageTitle='Login — StayNest'; require __DIR__.'/../includes/header.php';
?>
<div class="container"><div class="card auth-card border-0 shadow p-4 p-md-5">
<h2 class="fw-bold">Welcome back</h2><p class="text-muted">Login to manage your shortlisted PGs.</p>
<?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post">
<input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
<label class="form-label">Email</label><input name="email" type="email" class="form-control mb-3" required>
<label class="form-label">Password</label><input name="password" type="password" class="form-control mb-4" required>
<button class="btn btn-primary w-100">Login</button>
</form><div class="small text-muted mt-3">Demo: demo@example.com / password</div>
<p class="text-center mt-4 mb-0">New here? <a href="signup.php">Create an account</a></p>
</div></div>
<?php require __DIR__.'/../includes/footer.php'; ?>

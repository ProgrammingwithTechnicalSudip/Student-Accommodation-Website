<?php
session_start(); require_once __DIR__.'/../config/database.php';
if(!empty($_SESSION['user'])){header('Location: ../index.php');exit;}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name=trim($_POST['name']??''); $email=trim($_POST['email']??''); $phone=trim($_POST['phone']??''); $pass=$_POST['password']??'';
  if(strlen($name)<2 || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($pass)<6) $error='Enter valid details. Password must be at least 6 characters.';
  else{
    $check=$pdo->prepare("SELECT id FROM users WHERE email=?");$check->execute([$email]);
    if($check->fetch()) $error='Email is already registered.';
    else{
      $stmt=$pdo->prepare("INSERT INTO users(name,email,password,phone) VALUES(?,?,?,?)");
      $stmt->execute([$name,$email,password_hash($pass,PASSWORD_DEFAULT),$phone]);
      $_SESSION['user']=['id'=>(int)$pdo->lastInsertId(),'name'=>$name,'email'=>$email];
      header('Location: ../index.php');exit;
    }
  }
}
$pageTitle='Create Account — StayNest'; require __DIR__.'/../includes/header.php';
?>
<div class="container"><div class="card auth-card border-0 shadow p-4 p-md-5">
<h2 class="fw-bold">Create your account</h2><p class="text-muted">Save PGs and manage your shortlist.</p>
<?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post" novalidate>
<label class="form-label">Full name</label><input name="name" class="form-control mb-3" required>
<label class="form-label">Email</label><input name="email" type="email" class="form-control mb-3" required>
<label class="form-label">Phone</label><input name="phone" class="form-control mb-3">
<label class="form-label">Password</label><input name="password" type="password" class="form-control mb-4" required minlength="6">
<button class="btn btn-primary w-100">Create Account</button>
</form><p class="text-center mt-4 mb-0">Already registered? <a href="login.php">Login</a></p>
</div></div>
<?php require __DIR__.'/../includes/footer.php'; ?>

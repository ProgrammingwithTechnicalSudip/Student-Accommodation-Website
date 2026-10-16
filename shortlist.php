<?php
session_start(); require_once __DIR__.'/config/database.php';
if(!empty($_GET['format']) && $_GET['format']==='json'){
 header('Content-Type: application/json; charset=utf-8');
 if(empty($_SESSION['user'])){echo json_encode(['success'=>true,'data'=>[]]);exit;}
 $stmt=$pdo->prepare("SELECT p.id,p.name,p.city,p.price,p.rating,p.image
 FROM interested_users i JOIN properties p ON p.id=i.property_id
 WHERE i.user_id=? ORDER BY i.created_at DESC");
 $stmt->execute([(int)$_SESSION['user']['id']]);
 echo json_encode(['success'=>true,'data'=>$stmt->fetchAll()]); exit;
}
$pageTitle='My Shortlist — StayNest'; require __DIR__.'/includes/header.php';
if(empty($_SESSION['user'])):
?>
<div class="container py-5"><div class="empty p-5 text-center"><i class="bi bi-lock fs-1 text-muted"></i><h2 class="mt-3">Login required</h2><p class="text-muted">Please login to view your shortlisted properties.</p><a class="btn btn-primary" href="auth/login.php">Login</a></div></div>
<?php else:
$stmt=$pdo->prepare("SELECT p.* FROM interested_users i JOIN properties p ON p.id=i.property_id WHERE i.user_id=? ORDER BY i.created_at DESC");
$stmt->execute([(int)$_SESSION['user']['id']]);$items=$stmt->fetchAll();
?>
<main class="container py-5"><div class="d-flex justify-content-between align-items-center mb-4"><div><h1 class="section-title">My Shortlist</h1><p class="text-muted mb-0">Properties you are interested in.</p></div><a href="index.php" class="btn btn-primary">Explore more</a></div>
<div class="row g-4" id="shortlistGrid">
<?php if(!$items): ?><div class="col-12"><div class="empty p-5 text-center"><h4>No shortlisted properties</h4><p class="text-muted">Use the heart button on any property to save it here.</p></div></div>
<?php else: foreach($items as $p): ?>
<div class="col-md-6 col-lg-4" id="short-<?= (int)$p['id'] ?>"><div class="card property-card h-100"><img src="<?= htmlspecialchars($p['image']) ?>" class="property-img card-img-top"><div class="card-body"><span class="badge badge-soft"><?= htmlspecialchars($p['gender']) ?></span><h5 class="mt-3"><?= htmlspecialchars($p['name']) ?></h5><p class="text-muted"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($p['city']) ?></p><div class="price mb-3">₹<?= number_format((float)$p['price']) ?>/month</div><div class="d-flex gap-2"><a class="btn btn-primary flex-grow-1" href="property.php?id=<?= (int)$p['id'] ?>">View</a><button class="btn btn-outline-danger" onclick="removeShortlist(<?= (int)$p['id'] ?>,this)"><i class="bi bi-heartbreak"></i></button></div></div></div></div>
<?php endforeach; endif; ?>
</div></main>
<script>
async function removeShortlist(id,btn){
 btn.disabled=true;
 const r=await fetch('api/interest.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({property_id:id,action:'remove'})});
 const j=await r.json();
 if(j.success){document.getElementById('short-'+id).remove();}
 btn.disabled=false;
}
</script>
<?php endif; require __DIR__.'/includes/footer.php'; ?>

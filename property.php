<?php
require_once __DIR__.'/config/database.php';
$id=(int)($_GET['id'] ?? 0);
$stmt=$pdo->prepare("SELECT p.*, GROUP_CONCAT(a.name ORDER BY a.name SEPARATOR '||') amenities
                    FROM properties p
                    LEFT JOIN property_amenities pa ON pa.property_id=p.id
                    LEFT JOIN amenities a ON a.id=pa.amenity_id
                    WHERE p.id=? GROUP BY p.id");
$stmt->execute([$id]); $property=$stmt->fetch();
if(!$property){http_response_code(404);die('Property not found.');}
$amenities=$property['amenities']?explode('||',$property['amenities']):[];
$pageTitle=$property['name'].' — StayNest';
require __DIR__.'/includes/header.php';
?>
<main class="container py-5">
  <a href="index.php" class="text-decoration-none"><i class="bi bi-arrow-left"></i> Back to properties</a>
  <div class="row g-4 mt-2">
    <div class="col-lg-7">
      <img id="mainImage" src="<?= htmlspecialchars($property['image']) ?>" class="w-100 detail-img" alt="<?= htmlspecialchars($property['name']) ?>">
      <div class="row g-2 mt-2">
        <?php for($i=0;$i<4;$i++): ?>
        <div class="col-3"><img src="<?= htmlspecialchars($property['image']) ?>" class="thumb" onclick="document.getElementById('mainImage').src=this.src" alt="Property image"></div>
        <?php endfor; ?>
      </div>
    </div>
    <div class="col-lg-5">
      <span class="badge badge-soft mb-2"><?= htmlspecialchars($property['gender']) ?></span>
      <h1 class="fw-bold"><?= htmlspecialchars($property['name']) ?></h1>
      <p class="text-muted"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($property['address']) ?>, <?= htmlspecialchars($property['city']) ?></p>
      <div class="d-flex align-items-center gap-3 mb-3"><span class="rating"><i class="bi bi-star-fill"></i> <?= htmlspecialchars($property['rating']) ?>/5</span><span class="text-muted">Student accommodation</span></div>
      <div class="price mb-4">₹<?= number_format((float)$property['price']) ?> <small class="text-muted fw-normal">/ month</small></div>
      <p><?= nl2br(htmlspecialchars($property['description'])) ?></p>
      <button class="btn btn-primary btn-lg" id="interestDetail"><i class="bi bi-heart"></i> Add to shortlist</button>
      <div id="detailMessage" class="mt-3"></div>
    </div>
  </div>
  <section class="mt-5"><h3 class="section-title">Amenities</h3><div class="d-flex flex-wrap gap-2 mt-3">
    <?php foreach($amenities as $a): ?><span class="amenity"><i class="bi bi-check2-circle text-primary me-1"></i><?= htmlspecialchars($a) ?></span><?php endforeach; ?>
  </div></section>
</main>
<script>
document.getElementById('interestDetail').addEventListener('click',async function(){
 const btn=this; btn.disabled=true;
 try{
   const r=await fetch('api/interest.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({property_id:<?= $id ?>,action:'toggle'})});
   const j=await r.json();
   if(r.status===401){location.href='auth/login.php?redirect='+encodeURIComponent(location.pathname+location.search);return;}
   document.getElementById('detailMessage').innerHTML='<div class="alert alert-success">'+j.message+'</div>';
   btn.innerHTML=j.interested?'<i class="bi bi-heart-fill"></i> Shortlisted':'<i class="bi bi-heart"></i> Add to shortlist';
 }catch(e){document.getElementById('detailMessage').innerHTML='<div class="alert alert-danger">Could not update shortlist.</div>';}
 btn.disabled=false;
});
</script>
<?php require __DIR__.'/includes/footer.php'; ?>

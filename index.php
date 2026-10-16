<?php
require_once __DIR__.'/config/database.php';
$pageTitle='StayNest — Find Your Student Home';
$cities=$pdo->query("SELECT DISTINCT city FROM properties ORDER BY city")->fetchAll(PDO::FETCH_COLUMN);
require __DIR__.'/includes/header.php';
?>
<section class="hero py-5">
  <div class="container py-4">
    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <span class="badge bg-light text-primary mb-3">STUDENT ACCOMMODATION</span>
        <h1>Find a PG that feels like home.</h1>
        <p class="lead opacity-75">Explore verified-style accommodation options by city, budget and preference.</p>
        <div class="row g-2 mt-4">
          <div class="col-6 col-md-4"><div class="hero-stat"><strong>6+</strong><br><small>PG options</small></div></div>
          <div class="col-6 col-md-4"><div class="hero-stat"><strong>3</strong><br><small>Search filters</small></div></div>
          <div class="col-12 col-md-4"><div class="hero-stat"><strong>24/7</strong><br><small>Online access</small></div></div>
        </div>
      </div>
      <div class="col-lg-5 text-center"><i class="bi bi-buildings display-1"></i><p class="mt-3">Smart, simple accommodation discovery</p></div>
    </div>
  </div>
</section>

<div class="container search-card">
  <form id="filterForm" class="card border-0 shadow p-3 p-md-4">
    <div class="row g-3 align-items-end">
      <div class="col-md-4"><label class="form-label fw-semibold">City</label>
        <select name="city" class="form-select"><option value="">All cities</option><?php foreach($cities as $city): ?><option><?= htmlspecialchars($city) ?></option><?php endforeach; ?></select>
      </div>
      <div class="col-md-3"><label class="form-label fw-semibold">Maximum budget</label>
        <select name="budget" class="form-select"><option value="">Any budget</option><option value="7000">₹7,000</option><option value="8000">₹8,000</option><option value="9000">₹9,000</option><option value="10000">₹10,000</option><option value="12000">₹12,000</option></select>
      </div>
      <div class="col-md-3"><label class="form-label fw-semibold">Gender</label>
        <select name="gender" class="form-select"><option value="">Any</option><option>Male</option><option>Female</option></select>
      </div>
      <div class="col-md-2"><button class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filter</button></div>
    </div>
  </form>
</div>

<main class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div><h2 class="section-title mb-1">Explore properties</h2><span id="resultCount" class="text-muted">Loading...</span></div>
    <a href="shortlist.php" class="btn btn-outline-primary"><i class="bi bi-heart"></i> Shortlist</a>
  </div>
  <div id="propertyGrid" class="row g-4"></div>

  <section class="mt-5">
    <div class="card border-0 shadow-sm p-4">
      <div class="d-flex justify-content-between align-items-center mb-3"><div><h3 class="mb-1">Your shortlist</h3><p class="text-muted mb-0">React component integration.</p></div></div>
      <div id="reactShortlist" class="react-box"></div>
    </div>
  </section>
</main>

<script>
window.reactUserLoggedIn = <?= !empty($_SESSION['user']) ? 'true':'false' ?>;
</script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const root=document.getElementById('reactShortlist');
  if(!root || !window.React) return;
  const e=React.createElement;
  function Shortlist(){
    const [items,setItems]=React.useState([]);
    const [loading,setLoading]=React.useState(true);
    async function refresh(){
      setLoading(true);
      try{
        const r=await fetch('shortlist.php?format=json');
        const j=await r.json(); setItems(j.data||[]);
      }catch(x){setItems([]);} finally{setLoading(false);}
    }
    React.useEffect(()=>{refresh();window.refreshReactShortlist=refresh;},[]);
    if(loading) return e('div',{className:'text-muted'},'Loading shortlist...');
    if(!window.reactUserLoggedIn) return e('div',{className:'alert alert-light border mb-0'},'Login to use the React shortlist feature.');
    if(!items.length) return e('div',{className:'text-muted'},'No shortlisted properties yet. Click the heart on a property.');
    return e('div',{className:'d-flex flex-wrap gap-2'},items.map(x=>e('a',{key:x.id,href:'property.php?id='+x.id,className:'btn btn-outline-primary'},e('i',{className:'bi bi-heart-fill me-1'}),x.name)));
  }
  ReactDOM.createRoot(root).render(e(Shortlist));
});
</script>
<?php require __DIR__.'/includes/footer.php'; ?>

document.addEventListener('DOMContentLoaded', () => {
  const grid=document.getElementById('propertyGrid');
  const filterForm=document.getElementById('filterForm');
  const resultCount=document.getElementById('resultCount');

  async function loadProperties(params=new URLSearchParams()){
    if(!grid) return;
    grid.innerHTML='<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Loading properties...</p></div>';
    try{
      const res=await fetch('api/properties.php?'+params.toString(),{headers:{'X-Requested-With':'XMLHttpRequest'}});
      const json=await res.json();
      if(!json.success) throw new Error('Could not load properties');
      renderProperties(json.data);
      if(resultCount) resultCount.textContent=`${json.data.length} properties found`;
    }catch(e){
      grid.innerHTML='<div class="col-12"><div class="alert alert-danger">Unable to load properties. Please try again.</div></div>';
    }
  }

  function renderProperties(items){
    if(!items.length){
      grid.innerHTML='<div class="col-12"><div class="empty p-5 text-center"><i class="bi bi-search fs-1 text-muted"></i><h4 class="mt-3">No properties found</h4><p class="text-muted">Try changing your filters.</p></div></div>';
      return;
    }
    grid.innerHTML=items.map(p=>`
      <div class="col-sm-6 col-lg-4">
        <div class="card property-card h-100">
          <img src="${escapeHtml(p.image)}" class="property-img card-img-top" alt="${escapeHtml(p.name)}">
          <div class="card-body">
            <div class="d-flex justify-content-between gap-2">
              <span class="badge badge-soft">${escapeHtml(p.gender)}</span>
              <span class="rating"><i class="bi bi-star-fill"></i> ${p.rating}</span>
            </div>
            <h5 class="card-title mt-3 mb-1">${escapeHtml(p.name)}</h5>
            <p class="text-muted mb-2"><i class="bi bi-geo-alt"></i> ${escapeHtml(p.city)}</p>
            <div class="price mb-3">₹${Number(p.price).toLocaleString('en-IN')} <small class="text-muted fw-normal">/ month</small></div>
            <div class="d-flex gap-2">
              <a href="property.php?id=${p.id}" class="btn btn-primary flex-grow-1">View Details</a>
              <button class="btn btn-outline-primary interest-btn" data-id="${p.id}" title="Shortlist"><i class="bi bi-heart"></i></button>
            </div>
          </div>
        </div>
      </div>`).join('');
    document.querySelectorAll('.interest-btn').forEach(btn=>btn.addEventListener('click',()=>toggleInterest(btn)));
  }

  async function toggleInterest(btn){
    const old=btn.innerHTML;
    btn.disabled=true; btn.innerHTML='<span class="spinner-border spinner-small"></span>';
    try{
      const res=await fetch('api/interest.php',{
        method:'POST',headers:{'Content-Type':'application/json'},
        body:JSON.stringify({property_id:Number(btn.dataset.id),action:'toggle'})
      });
      const json=await res.json();
      if(res.status===401){ location.href='auth/login.php?redirect='+encodeURIComponent(location.pathname); return; }
      if(!json.success) throw new Error(json.message);
      btn.innerHTML=json.interested?'<i class="bi bi-heart-fill"></i>':'<i class="bi bi-heart"></i>';
      showToast(json.message,'success');
      if(window.refreshReactShortlist) window.refreshReactShortlist();
    }catch(e){ btn.innerHTML=old; showToast(e.message||'Something went wrong','danger'); }
    finally{btn.disabled=false;}
  }

  if(filterForm){
    filterForm.addEventListener('submit',e=>{
      e.preventDefault();
      const params=new URLSearchParams(new FormData(filterForm));
      loadProperties(params);
    });
    filterForm.querySelectorAll('select').forEach(x=>x.addEventListener('change',()=>filterForm.requestSubmit()));
    loadProperties();
  }

  function escapeHtml(v){return String(v??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));}
  function showToast(message,type='success'){
    let el=document.getElementById('toastBox');
    if(!el){el=document.createElement('div');el.id='toastBox';el.className='position-fixed bottom-0 end-0 p-3';el.style.zIndex=1080;document.body.appendChild(el);}
    el.innerHTML=`<div class="alert alert-${type} shadow mb-0">${escapeHtml(message)}</div>`;
    setTimeout(()=>el.innerHTML='',2500);
  }
  window.StayNest={loadProperties,toggleInterest};
});

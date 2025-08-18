document.getElementById('epka-select-all')?.addEventListener('click', function(e){
  e.preventDefault();
  document.querySelectorAll('.epka-form input[type="checkbox"]').forEach(cb=>cb.checked=true);
});
document.getElementById('epka-deselect-all')?.addEventListener('click', function(e){
  e.preventDefault();
  document.querySelectorAll('.epka-form input[type="checkbox"]').forEach(cb=>cb.checked=false);
});
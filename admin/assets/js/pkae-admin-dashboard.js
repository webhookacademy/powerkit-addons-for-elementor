document.getElementById('pkae-select-all')?.addEventListener('click', function(e){
  e.preventDefault();
  document.querySelectorAll('.pkae-form input[type="checkbox"]').forEach(cb=>cb.checked=true);
});
document.getElementById('pkae-deselect-all')?.addEventListener('click', function(e){
  e.preventDefault();
  document.querySelectorAll('.pkae-form input[type="checkbox"]').forEach(cb=>cb.checked=false);
});
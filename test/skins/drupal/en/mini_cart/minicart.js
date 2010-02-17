function xlite_minicart_toggle(id) {
  var el = document.getElementById(id);
  el.className = (el.className != 'collapsed') ? 'collapsed' : 'expanded';
}

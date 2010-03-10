function xlite_minicart_toggle(id) {
	var el = document.getElementById(id);
	var c = el.className.replace('collapsed', 'expanded');
	if (c == el.className)
		c = el.className.replace('expanded', 'collapsed');
	el.className = c;
}

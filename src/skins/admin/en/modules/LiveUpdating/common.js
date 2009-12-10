function openCloseModuleUpdatesSection(name)
{
	if (obj = document.getElementById('module_updates_section_'+name)) {
		obj.style.display = (obj.style.display == '') ? 'none' : '';
	}

	if (obj = document.getElementById('module_desc_section_'+name)) {
		obj.style.display = (obj.style.display == '') ? 'none' : '';
	}
}

function setChecked(form, input, check)
{
    var elements = document.forms[form].elements[input];

    if ( elements.length > 0 ) {
        for (var i = 0; i < elements.length; i++) {
            elements[i].checked = check;
        }
    } else {
        elements.checked = check;
    }
}

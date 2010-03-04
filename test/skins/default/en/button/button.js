/* SVN $Id$ */

function setFormAttribute(form, name, value)
{   
    form.elements[name].value = value;
}

function setFormAction(form, action)
{   
    setFormAttribute('action', action);
}

function submitForm(form, attrs)
{
	for (name in attrs) {
		if (form.elements[name]) {
			form.elements[name].value = attrs[name];
		}
	}

	form.submit();
}

function submitFormDefault(form, action)
{
	var attrs = [];
	attrs['action'] = action;

	submitForm(form, attrs);
}


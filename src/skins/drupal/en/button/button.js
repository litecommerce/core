/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

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
  jQuery.each(
    attrs,
    function (name, value) {
      var e = form.elements.namedItem(name);
      if (e) {
        e.value = value;
      }
    }
  );

	jQuery(form).submit();
}

function submitFormDefault(form, action)
{
	var attrs = [];
  if (action !== null) {
  	attrs['action'] = action;
  }

	submitForm(form, attrs);
}


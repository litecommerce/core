/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

// TODO: jQuery rework

function setChecked(form, input, check)
{
    var elements = document.forms[form].elements[input];

    for (var i = 0; i < elements.length; i++) {
        elements[i].checked = check;
    }
}

function setHeaderChecked()
{
  var Element = document.getElementById("activate_products");
    if (Element && !Element.checked) {
      Element.checked = true;
    }
}

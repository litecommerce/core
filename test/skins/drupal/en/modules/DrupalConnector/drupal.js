/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Drupal connector functions
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id: products_list.js 2161 2010-03-04 10:04:46Z max $
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

// FIXME - must be improved and synchronized with the "default" skin

// Drupal-specific URLs
$(document).ready(
  function() {
    URLHandler.baseURLPart = '?q=store/';
    URLHandler.argSeparator = '/';
    URLHandler.nameValueSeparator = '-';

    URLHandler.getMainParamValue = function(name, params)
    {
      return params[name];
    }
  }
);

/**
 * List of all "get"-forms 
 */
var xliteForms = [];

/**
 * Semaphore 
 */
var submitStarted = false;


/**
 * Prepare submit params for the forms having "GET" method
 * 
 * @param HTMLFormElement $form curretn form
 *  
 * @return void
 * @since  3.0.0 EE
 */
function drupalOnSubmitGetForm(form)
{
	if (!submitStarted) {

		submitStarted = true;

		params = xliteForms[form.getAttribute('name')];
		params.splice(params.indexOf('q'), 1);

		result = [];

		for (i = 0; i < params.length; i++) {
			element = form.elements[params[i]];
			result[params[i]] = element.value;
			element.parentNode.removeChild(element);
		}

		form.q.value = URLHandler.buildURL(result);
	}
}


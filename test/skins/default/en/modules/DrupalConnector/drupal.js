/* SVN $Id$ */

/**
 * List of all "get"-forms 
 */
var xliteForms = [];

/**
 * Semaphore 
 */
var submitStarted = false;


/**
 * Compose Drupal URL 
 * 
 * @param array $params list of URL params
 *  
 * @return string
 * @since  3.0.0 EE
 */
function drupalBuldURL(params)
{
	// TODO - add the AJAX request here

	result = 'store';
	mainParams = ['target','action'];

	for (i = 0; i < mainParams.length; i++) {
		result += '/' + params[mainParams[i]];
		delete params[mainParams[i]];
	}

	for (name in params) {
		result += '/' + name + '-' + params[name];
	}

	return result;
}

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

		form.q.value = drupalBuldURL(result);
	}
}


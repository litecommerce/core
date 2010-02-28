/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * BlockUI-based popup widget
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id: popup.js 2108 2010-02-25 14:26:34Z max $
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

function blockUIPopup(data)
{
	$.blockUI(
    	{
        	message: '<a href="#" class="close-link" onclick="javascript: blockUIPopupClose(); return false;"></a><div class="block-container"><div class="block-subcontainer">' + data + '</div></div>'
        }
	);
	$('.blockOverlay')
		.attr('title', 'Click to unblock')
		.click(blockUIPopupClose); 
}

function blockUIPopupClose()
{
	$.unblockUI();
}

function blockUIPopupWait()
{
	blockUIPopup('<div class="block-wait">Please wait ...</div>');
}

function blockUIPopupFormTarget(form)
{
    if (form) {
        blockUIPopupWait();

        $.ajax(
			{
				type: form.method.toUpperCase(),
            	url:  form.action,
            	data: $(form).serialize(),
            	success: function(data, s) {
					data = blockUIPopupPreprocess(data, s);
	                blockUIPopup(data);
					blockUIPopupPostprocess();
				},
				complete: function(xhr, s) {
                    blockUIPopupXHRPreprocess(xhr, s);
				}
            }
        );
    }
}

function blockUIPopupXHRPreprocess(xhr, s)
{
	// Redirect
	if (xhr.status == 278) {

		blockUIPopupClose();
		var url = xhr.getResponseHeader('Location');
		if (url) {
			self.location = url;

		} else {
			self.location.reload(true);
		}
	}
}

function blockUIPopupPreprocess(data, s)
{
	return data;
}

function blockUIPopupPostprocess()
{
	$('.blockMsg form').each(
		function() {
	        $(this).submit(
				function() {
					blockUIPopupFormTarget(this);
					return false;
				}
			);
		}
	);
}

$(document).ready(
	function() {
		$.blockUI.defaults.css = {};
		$.blockUI.defaults.centerX =         true;
		$.blockUI.defaults.centerY =         true;
		$.blockUI.defaults.bindEvents =      true;
		$.blockUI.defaults.constrainTabKey = true;
		$.blockUI.defaults.showOverlay =     true;
		$.blockUI.defaults.focusInput =      true;
		$.blockUI.defaults.fadeIn =          0;
		$.blockUI.defaults.fadeOut =         0;
	}
);

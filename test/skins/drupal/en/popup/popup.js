/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * BlockUI-based popup widget
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

// Display block message
function blockUIPopup(data)
{
  $.blockUI(
    {
      message: '<a href="#" class="close-link" onclick="javascript: blockUIPopupClose(); return false;"></a><div class="block-container"><div class="block-subcontainer">' + data + '</div></div>'
    }
  );

  // Reposition
  $('.blockMsg').css('left', Math.round(($(window).width() - $('.blockMsg').width()) / 2) + 'px');

  // Modify overlay
  $('.blockOverlay')
    .attr('title', 'Click to unblock')
    .click(blockUIPopupClose); 
}

// Close message box
function blockUIPopupClose()
{
  $.unblockUI();
}

// Display 'Please wait' message
function blockUIPopupWait()
{
  blockUIPopup('<div class="block-wait">Please wait ...</div>');
}

// Open URL-based popup
function openBlockUIPopupURL(url)
{
  if (!hasAJAXSupport()) {
    return false;
  }

  return $.ajax(
    {
      type:     'get',
      url:      url,
      success:  function(data, s) {
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

// Open form-based popup
function blockUIPopupFormTarget(form)
{
  if (!form || !hasAJAXSupport()) {
    return false;
  }

  blockUIPopupWait();

  form = $(form).eq(0);

  return $.ajax(
    {
      type:     form.attr('method'),
      url:      form.attr('action'),
      data:     form.serialize(),
      success:  function(data, s) {
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

function blockUIPopupXHRPreprocess(xhr, s)
{
  if (xhr.status == 278) {

    // Redirect

    blockUIPopupClose();
    var url = xhr.getResponseHeader('Location');
    if (url) {
      self.location = url;

    } else {
      self.location.reload(true);
    }

  } else if (xhr.status == 279) {

    // Internal redirect

    var url = xhr.getResponseHeader('Location');
    if (url) {
      $.ajax(
        {
          type:     'get',
          url:      url,
          success:  function(data, s) {
            data = blockUIPopupPreprocess(data, s);
            blockUIPopup(data);
            blockUIPopupPostprocess();
          },
          complete: function(xhr, s) {
            blockUIPopupXHRPreprocess(xhr, s);
          }
        }
      );

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
  $('.blockMsg form').submit(
    function(event) {
      event.stopPropagation();
      $('button', this).attr('disabled', 'disabled');
      blockUIPopupFormTarget(this);
      return false;
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

/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Top message controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */
var MESSAGE_INFO    = 'info';
var MESSAGE_WARNING = 'warning';
var MESSAGE_ERROR   = 'error';

/**
 * Controller
 */

// Constructor
function TopMessages(container) {
  if (!container) {
    return false;
  }

  this.container = $(container).eq(0);
  if (!this.container.length) {
    return false;
  }

  // Add listeners
  var o = this;

  // Close button
  $('a.close', this.container).click(
    function(event) {
      event.stopPropagation();
      o.clearRecords();

      return false;
    }
  ).hover(
    function() {
      $(this).addClass('close-hover');
    },
    function() {
      $(this).removeClass('close-hover');
    }
  );

  // Global event
  $(document).bind(
    'message',
    function(event, text, type) {
      o.messageHandler(text, type);
    }
  );

  // AJAX global event
  $(document).bind(
    'ajaxComplete',
    function(event, xhr, options) {
      o.ajaxCompleteHandler(xhr, options);
    }
  );

  // Remove dump items (W3C compatibility)
  $('li.dump', this.container).remove();

  // Fix position: fixed
  this.msie6 = $.browser.msie && parseInt($.browser.version) < 7;
  if (this.msie6) {
    this.container.css('position', 'absolute');
    this.container.css('border-style', 'solid');
    $('ul', this.container).css('border-style', 'solid');
  }

  // Initial show
  if (!this.isVisible() && $('li', this.container).length) {
    setTimeout(
      function() {
        o.show();

        // Set initial timers
        $('li.' + MESSAGE_INFO, o.container).each(
          function() {
            o.setTimer(this);
          }
        );
      },
      1000
    );

  } else {

    // Set initial timers
    $('li.' + MESSAGE_INFO, this.container).each(
      function() {
        o.setTimer(this);
      }
    );
  }
}

/**
 * Properties
 */
TopMessages.prototype.container = null;
TopMessages.prototype.to = null;

TopMessages.prototype.ttl = 5000;

/**
 * Methods
 */

// Check visibility
TopMessages.prototype.isVisible = function()
{
  return this.container.css('display') != 'none';
}

// Show widget
TopMessages.prototype.show = function()
{
  this.container.slideDown();
}

// Hide widget
TopMessages.prototype.hide = function()
{
  this.container.slideUp();
}

// Add record
TopMessages.prototype.addRecord = function(text, type)
{
  if (
    !type
    || -1 == [MESSAGE_INFO, MESSAGE_WARNING, MESSAGE_ERROR].indexOf(type)
  ) {
    type = MESSAGE_INFO; 
  }

  var li = document.createElement('LI');
  li.innerHTML = text;
  li.className = type;
  li.style.display = 'none';

  $('ul', this.container).append(li);

  if (
    $('li', this.container).length
    && !this.isVisible()
  ) {
    this.show();
  }

  $(li).slideDown('fast');

  if (type == MESSAGE_INFO) {
    this.setTimer(li);
  }
}

// Clear record
TopMessages.prototype.hideRecord = function(li)
{
  if ($('li:not(.remove)', this.container).length == 1) {
    this.clearRecords();

  } else {
    $(li).addClass('remove').slideUp(
      'fast',
      function() {
        $(this).remove();
      }
    );
  }
}

// Clear all records
TopMessages.prototype.clearRecords = function()
{
  this.hide();
  $('li', this.container).remove();
}

// Set record timer
TopMessages.prototype.setTimer = function(li)
{
  li = $(li).get(0);

  if (li.timer) {
    clearTimeout(li.timer);
    li.timer = false;
  }

  var o = this;
  li.timer = setTimeout(
    function() {
      o.hideRecord(li);
    },
    this.ttl
  );
}

// onmessage event handler
TopMessages.prototype.messageHandler = function(text, type)
{
  this.addRecord(text, type);
}

// AJAX complete request global event handler
TopMessages.prototype.ajaxCompleteHandler = function(xhr)
{
  var messages = xhr.getResponseHeader('AJAX-Top-Messages');
  if (messages) {
    messages = messages.split(/\|/);
    for (var i = 0; i < messages.length; i++) {
      var message = $.trim(messages[i]);
      var m = message.match(/\[([a-z]+)\]$/);
      var type = MESSAGE_INFO;
      if (m) {
        message = message.substr(0, message.length - m[0].length);
        type = m[1];
      }

      if (message) {
        this.addRecord(message, type);
      }
    }
  }
}

// Initialize
$(document).ready(
  function () {
    new TopMessages($('#top_messages').eq(0));
  }
);

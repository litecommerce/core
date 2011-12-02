/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Advanced text-based field controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

function AdvancedInputHandler()
{
  // block initialization
  this.block = jQuery(this.pattern);
}

AdvancedInputHandler.prototype.block = null;
AdvancedInputHandler.prototype.pattern = 'div.advanced-input-text';

/**
 * Main changing actions method. Widget is prepared and actions are added to it.
 */
AdvancedInputHandler.prototype.changeActions = function ()
{
  var o = this;

  this.block.each(
    function () {
      this.widget = o.initWidget(this);
      o.addActions(this.widget);
    }
  );
}

/**
 * Initialization of widget
 */
AdvancedInputHandler.prototype.initWidget = function (obj)
{
  var widget = {};

  widget.label        = jQuery('.original-label', obj);
  widget.inputBlock   = jQuery('.original-input', obj);
  widget.cancel       = jQuery('.cancel-input', widget.inputBlock);
  widget.input        = jQuery('input', widget.inputBlock).eq(0);

  if ('undefined' == typeof widget.input.cancelValue) {
    widget.input.cancelValue  = widget.input.val();
  }

  return widget;
}

/**
 * Adding actions to widget
 */
AdvancedInputHandler.prototype.addActions = function (widget)
{
  var o = this;

  widget.label.click(
    function () {
      widget.label.hide();
      widget.inputBlock.show();
      widget.input.focus();

      setTimeout(
        function () {

          jQuery('body').bind(
            'click',
            function () {
              o.enterValue(widget);
            }
          );

          widget.inputBlock.bind(
            'click',
            function(event) {
              event.stopPropagation();
            }
          );
        },
        50
      );

    }
  );

  widget.cancel.click(
    function () {
      o.cancel(widget);
    }
  );
}

AdvancedInputHandler.prototype.enterValue = function (widget)
{
  widget.label.html(widget.input.val()).show();
  widget.inputBlock.hide();
  widget.input.cancelValue = widget.input.val();

  jQuery('body').unbind('click');
}

AdvancedInputHandler.prototype.cancel = function (widget)
{
  widget.input.val(widget.input.cancelValue);
  this.enterValue(widget);
}

core.bind(
  'load',
  function(event) {
    var advancedInputHandler = new AdvancedInputHandler();

    advancedInputHandler.changeActions();
  }
);

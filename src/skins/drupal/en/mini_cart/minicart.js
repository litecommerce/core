/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Minicart controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

/**
 * Controller
 */

function MinicartController(base)
{
  this.callSupermethod('constructor', arguments);

  this.block = new MinicartView(this.base);

  var o = this;

  core.bind(
    'updateCart',
    function(event, data) {
      o.block.load();
    }
  );
}

extend(MinicartController, AController);

// Controller name
MinicartController.prototype.name = 'MinicartController';

// Find pattern
MinicartController.prototype.findPattern = '.lc-minicart-horizontal';

// Controller associated widget
MinicartController.prototype.block = null;

// Initialize controller
AController.prototype.initialize = function()
{
  var o = this;

  this.base.bind(
    'reload',
    function(event, box) {
      o.bind(box);
    }
  );
}

/**
 * Widget
 */

function MinicartView(base)
{
  this.callSupermethod('constructor', arguments);
}

extend(MinicartView, ALoadable);

// No shade widget
MinicartView.prototype.shadeWidget = false;

// Widget target
MinicartView.prototype.widgetTarget = 'cart';

// Widget class name
MinicartView.prototype.widgetClass = '\\XLite\\View\\Minicart';

// Expanded mode flag
MinicartView.prototype.isExpanded = false;

// Postprocess widget
MinicartView.prototype.postprocess = function(isSuccess)
{
  this.callSupermethod('postprocess', arguments);

  if (isSuccess) {
    var o = this;
    $('.toggle-button a', this.base).click(
      function(event) {
        event.stopPropagation();

        o.toggleViewMode(event);

        return false;
      }
    );

    if (this.isExpanded) {
      this.base.addClass('expanded').removeClass('collapsed');
    }
  }
}

// Toggle view mode
MinicartView.prototype.toggleViewMode = function()
{
  if (this.base.hasClass('expanded')) {
    this.base.removeClass('expanded').addClass('collapsed');
    this.isExpanded = false;

  } else {
    this.base.addClass('expanded').removeClass('collapsed');
    this.isExpanded = true;
  }
}

core.autoload(MinicartController);

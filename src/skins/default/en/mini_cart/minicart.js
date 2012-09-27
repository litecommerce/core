/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Minicart controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
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
      if (data.items || data.total) {
        o.block.load();
      }
    }
  );
}

extend(MinicartController, AController);

// Controller name
MinicartController.prototype.name = 'MinicartController';

// Find pattern
MinicartController.prototype.findPattern = '.lc-minicart';

// Controller associated widget
MinicartController.prototype.block = null;

// Initialize controller
MinicartController.prototype.initialize = function()
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

// Body handler is binded or not
MinicartView.prototype.bodyHandlerBinded = false;

// Postprocess widget
MinicartView.prototype.postprocess = function(isSuccess)
{
  this.callSupermethod('postprocess', arguments);

  if (isSuccess) {
    var o = this;

    // Get display mode
    var re = /lc-minicart-([^ ]+)/;
    if (!this.widgetParams && this.base.attr('class') && -1 != this.base.attr('class').search(re)) {
      if (!this.widgetParams) {
        this.widgetParams = {};
      }

      var m = this.base.attr('class').match(re);
      this.widgetParams.displayMode = m[1];
    }

    // Initialize view mode toggle mechanism
    this.base.click(
      function(event) {
        event.stopPropagation();

        o.toggleViewMode();

        return false;
      }
    );

    if (!this.bodyHandlerBinded) {
      jQuery('body').click(
        function (event) {
          o.toggleViewMode(false);
        }
      );

      this.bodyHandlerBinded = true;
    }

    jQuery('.items-list', this.base).click(
      function(event) {
        event.stopPropagation();
      }
    );

    if (this.isExpanded) {
      this.base.addClass('expanded').removeClass('collapsed');
    }
  }
}

// Toggle view mode
MinicartView.prototype.toggleViewMode = function(expand)
{
  if (expand !== true && expand !== false) {
      expand = !this.base.hasClass('expanded');
  }
  this.isExpanded = expand;

  if (expand) {
    this.base.addClass('expanded').removeClass('collapsed');

  } else {
    this.base.removeClass('expanded').addClass('collapsed');
  }
}

core.autoload(MinicartController);

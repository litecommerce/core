/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Product comparison
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

/**
 * Widget
 */
function ProductComparisonView(base)
{
  this.callSupermethod('constructor', arguments);
  var o = this;
  core.bind(
    'updateProductComparison',
    function(event, data) {
      o.load();
    }
  );
}

extend(ProductComparisonView, ALoadable);

ProductComparisonView.autoload = function(){
  new ProductComparisonView(jQuery('.product-comparison'));
};

// No shade widget
ProductComparisonView.prototype.shadeWidget = false;

// Widget target
ProductComparisonView.prototype.widgetTarget = 'main';

// Widget class name
ProductComparisonView.prototype.widgetClass = '\\XLite\\Module\\CDev\\ProductComparison\\View\\ProductComparison';

// Body handler is binded or not
ProductComparisonView.prototype.bodyHandlerBinded = false;

// Clear list
ProductComparisonView.prototype.clearList = function()
{
  this.load({action: 'clear'});

  return false;
}

// Postprocess widget
ProductComparisonView.prototype.postprocess = function(isSuccess)
{
  this.callSupermethod('postprocess', arguments);

  if (isSuccess) {
    var o = this;

    jQuery('.clear-list').click(
      function() {
        return o.clearList();
      }
    );
  }
}

core.autoload(ProductComparisonView);

/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Disables dragging
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

function disableDragging()
{
  var draggablePattern = '.products-grid .product, .products-list .product, .products-sidebar .product';
  jQuery(draggablePattern).draggable('disable').removeClass('ui-state-disabled');
}

core.bind(
  'load',
  function() {
    decorate(
      'ProductsListView',
      'postprocess',
      function(isSuccess, initial)
      {
        arguments.callee.previousMethod.apply(this, arguments);
        disableDragging();
      }
    )
  }
);

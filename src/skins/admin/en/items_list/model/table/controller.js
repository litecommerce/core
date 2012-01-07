/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Items list controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 */

// Main class
function TableItemsList(cell, URLParams, URLAJAXParams)
{
  this.container = jQuery('.items-list').eq(0);

  if (!this.container.length) {
    return;
  }

  this.cell = cell;
  this.URLParams = URLParams;
  this.URLAJAXParams = URLAJAXParams;

  // Common form support
  CommonForm.autoassign(this.container);

  this.addListeners();
}

extend(TableItemsList, ItemsList);

TableItemsList.prototype.listeners.pager = function(handler)
{
  jQuery('.table-pager .input input', handler.container).blur(
    function() {
      return !handler.process('pageId', this.value);
    }
  );
}

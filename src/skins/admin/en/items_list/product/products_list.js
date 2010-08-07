/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

// Products list class
function ProductsList(cell, URLParams, URLAJAXParams)
{
  if (!cell) {
    return;
  }

  this.constructor.prototype.constructor(cell, URLParams, URLAJAXParams);
}

// Change sort criterion
ItemsList.prototype.changeSortByMode = function(handler)
{
  return this.process('sortBy', $(handler).attr('class'));
}

// Change sort order
ItemsList.prototype.changeSortOrder = function()
{
  return this.process('sortOrder', ('asc' == this.URLParams.sortOrder) ? 'desc' : 'asc');
}

// Check all checkboxes in list
ItemsList.prototype.checkAll = function(handler)
{
  return this.container.find('input:checkbox.checkbox').attr('checked', $(handler).attr('checked') ? 'checked' : '');
}

ItemsList.prototype.listeners.sortByModes = function(handler)
{
  $('.sort-order .part.sort-crit a', handler.container).click(
    function() {
      return !handler.changeSortByMode(this);
    }
  );
}

ItemsList.prototype.listeners.sortOrderModes = function(handler)
{
  $('.sort-order .part.order-by a', handler.container).click(
    function() {
      return !handler.changeSortOrder();
    }
  );

}

ItemsList.prototype.listeners.checboxes = function(handler)
{
  $('input:checkbox.check-all', handler.container).click(
    function() {
      return handler.checkAll(this);
    }
  );
}


ProductsList.prototype = new ItemsList();
ProductsList.prototype.constructor = ItemsList;


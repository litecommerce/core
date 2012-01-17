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
  TableItemsList.superclass.constructor.apply(this, arguments);
}

extend(TableItemsList, ItemsList);

TableItemsList.prototype.form = null;

// Pager listener
TableItemsList.prototype.listeners.pager = function(handler)
{
  jQuery('.table-pager .input input', handler.container).change(
    function() {
      return !handler.process('pageId', this.value);
    }
  );

  jQuery('.table-pager a', handler.container).click(
    function() {
      return !(jQuery(this).hasClass('disabled') || handler.process('pageId', jQuery(this).data('pageId')));
    }
  );

}

// Item per page input listener
TableItemsList.prototype.listeners.pagesCount = function(handler)
{
  jQuery('select.page-length', handler.container).change(
    function() {
      return !handler.process('itemsPerPage', this.options[this.selectedIndex].value);
    }
  );
}

// Form listener
TableItemsList.prototype.listeners.form = function(handler)
{
  var form = handler.container.parents('form').eq(0);

  form.get(0).commonController.submitOnlyChanged = true;

  form.change(
    function () {
      var form = jQuery(this);
      var btn = form.find('button.submit');

      if (this.commonController.isChanged()) {
        form.addClass('changed');
        btn.each(
          function() {
            this.enable();
          }
        );

      } else {
        form.removeClass('changed');
        btn.each(
          function() {
            this.disable();
          }
        );
      }
    }
  );
}


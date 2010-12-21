/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Sort controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */
function SortBoxController(container)
{
  if (!container) {
    return false;
  }

  container = jQuery(container);

  if (!container.length) {
    return false;
  }

  this.container = container;

  this.form = this.container;

  // Add listeners
  var o = this;

  jQuery('select', this.container).change(
    function() {
      return o.changeSortCriterion(this);
    }
  );

  jQuery('a', this.container).click(
    function() {
      return !o.changeSortOrder(this);
    }
  );

}

// Properties
SortBoxController.prototype.container = null;
SortBoxController.prototype.form = null;

// Methods

// Change sort criterion
SortBoxController.prototype.changeSortCriterion = function(select)
{
  this.form.submit();

  return true;
}

// Change sort order
SortBoxController.prototype.changeSortOrder = function(link)
{
  var e = jQuery('input[name="sortOrder"]', this.form).eq(0);
  if (!e.length)  {
    e = document.createElement('INPUT')
    e.type = 'hidden';
    e.name = 'sortOrder';
    e = jQuery(this.form.get(0).appendChild(e));
  }

  e.attr('value', jQuery(link).hasClass('asc') ? 'desc' : 'asc');

  this.form.submit();

  return true;
}

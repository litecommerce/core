/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Advanced search controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */
function advancedSearchController(container)
{
  if (!container) {
    return false;
  }

  this.container = $(container).eq(0);
  if (!this.container.length) {
    return false;
  }

  // Add event handlers
  var o = this;
  $('a.reset', this.container).click(
    function() {
      return !o.reset();
    }
  );

}

// Rest form
advancedSearchController.prototype.reset = function()
{
  $('input[type="text"]', this.container).attr('value', '');

  $('select', this.container).each(
    function() {
      this.selectedIndex = 0;
      this.options[this.selectedIndex].selected = true;
    }
  );

  var checkedList = [
    'search_title',
    'search_description',
    'search_brief_description',
    'search_meta_tags',
    'search_extra_fields',
    'search_options',
    'search_subcategories',
  ];

  $('input[type="checkbox"]', this.container).each(
    function() {
      this.checked = this.id && $.inArray(this.id, checkedList) != -1;
    }
  );

  checkedList = [
    'search_logic_1'
  ];
  $('input[type="radio"]', this.container).each(
    function() {
      this.checked = this.id && $.inArray(this.id, checkedList) != -1;
    }
  );


  return true;
}

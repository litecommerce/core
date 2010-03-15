/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Extended options controller
 *  
 * @author  Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link    http://www.litecommerce.com/
 * @since   3.0.0
 */
function extendedOptionsController(container, names, options)
{
  if (!container) {
    return false;
  }

  this.container = $(container).eq(0);
  this.names = names;
  this.options = options;

  this.container.get(0).extendedOptionsController = this;

  // Add event handlers
  var o = this;

  $('.product-options input[type="radio"]', this.container).click(
    function() {
      o.changeOption(this);
    }
  );

  $('.product-options select', this.container).change(
    function() {
      o.changeOption(this);
    }
  );
}

extendedOptionsController.prototype.currentState = [];

extendedOptionsController.prototype.changeOption = function(elm)
{
  var state = this.getCurrentState();

  var id = -1;
  for (var i = 0; i < this.options.length && 0 > id; i++) {
    if (this.options[i].key == state) {
      id = i;
    }
  }

  if (0 <= id) {
    var cell = this.options[id];

    // Update quantity
    var elm = $('.product-quantity span', this.container);
    if (elm.length) {
      elm.html('(' + cell.quantity[0] + String.fromCharCode(8211) + cell.quantity[1] + ')');
    }

    // Update quantity controller
    var elm = $('.product-quantity', this.container).get(0);
    if (elm && typeof(elm.quantityController) != 'undefined') {
      elm.quantityController.min = cell.quantity[0];
      elm.quantityController.max = cell.quantity[1];
      elm.quantityController.change();
    }
  }
}

extendedOptionsController.prototype.getCurrentState = function()
{
  this.currentState = [];

  var o = this;
  $('.product-options input[type="radio"]:checked', this.container).each(
    function() {
      var m = this.id.match(/^product_option_(.+)_([0-9]+)$/);
      if (m) {
        var id = o.getClassIdByName(m[1]);
        o.currentState[id] = m[2];
      }
    }
  );

  $('.product-options select', this.container).each(
    function() {
      var m = this.name.match(/^product_option\[(.+)\]$/);
      if (m) {
        var id = o.getClassIdByName(m[1]);
        o.currentState[id] = this.options[s.selectedIndex].value;
      }
    }
  );

  return this.currentState.join('|');
}

extendedOptionsController.prototype.getClassIdByName = function(name)
{
  var id = -1;
  for (var i = 0; i < this.names.length && id < 0; i++) {
    if (this.names[i] == name) {
      id = i;
    }
  }

  return id;
}


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

function ShipAsBillHandler()
{
  this.flag = jQuery('#ship-as-bill');
  this.block = jQuery('.shipping-section');

  // Event handlers
  var o = this;

  this.flag.click(
    function(event) {
      return o.changeFieldsAccessability();
    }
  );

  this.changeFieldsAccessability();
}

ShipAsBillHandler.prototype.flag = null;
ShipAsBillHandler.prototype.block = null;

ShipAsBillHandler.prototype.changeFieldsAccessability = function()
{
  this.block.find('input, select, textarea').attr('disabled', this.flag.attr('checked') ? 'disabled' : '');
  this.flag.attr('disabled', '');
}

jQuery(document).ready(
  function(event) {
    var shipAsBillHandler = new ShipAsBillHandler();
  }
);

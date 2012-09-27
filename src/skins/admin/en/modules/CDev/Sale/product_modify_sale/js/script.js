/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Sale widget controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

function SalePriceValueBlock() {

  if (jQuery('#participate-sale').attr('checked') == '' ) {
    jQuery('.sale-discount-types').hide();
  }

  // Binding "Change" functionality to participate-sale checkbox
  jQuery('#participate-sale').bind('change', function () {
    if (this.checked) {
      jQuery('.sale-discount-types').show();
    } else {
      jQuery('.sale-discount-types').hide();
    }
  });
}

core.autoload(SalePriceValueBlock);
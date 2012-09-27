/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Sale widget controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

function SalePriceValue() {

  jQuery('.discount-type input:radio:checked').closest('ul.sale-discount').addClass('active');

  jQuery('.discount-type input:radio').bind('click', function () {

    jQuery('ul.sale-discount').removeClass('active');

    jQuery(this).closest('ul.sale-discount').addClass('active');

    var input = jQuery('#sale-price-value-' + jQuery(this).val());

    input.focus();

    jQuery('input[name="postedData[salePriceValue]"]').val(input.val());
  });

  jQuery('.sale-price-value input[type="text"]').bind('change', function () {
    jQuery('input[name="postedData[salePriceValue]"]').val(jQuery(this).val());
  });
}

core.autoload(SalePriceValue);
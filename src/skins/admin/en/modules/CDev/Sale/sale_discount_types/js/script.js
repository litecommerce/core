/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Sale widget controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.14
 */

function SalePriceValue() {
//  jQuery('.sale-price-value input[type="text"]').hide();

  jQuery('.discount-type input:radio:checked').closest('ul.sale-discount').addClass('active');

  jQuery('.discount-type input:radio').bind('click', function () {

    jQuery('ul.sale-discount').removeClass('active');

    jQuery(this).closest('ul.sale-discount').addClass('active');

    //jQuery('.sale-price-value input[type="text"]').hide();

    jQuery('#sale-price-value-' + jQuery(this).val()).focus();
  });

  jQuery('.sale-price-value input[type="text"]').bind('change', function () {
    jQuery('#sale-price-value').val(jQuery(this).val());
  });
}

core.autoload(SalePriceValue);
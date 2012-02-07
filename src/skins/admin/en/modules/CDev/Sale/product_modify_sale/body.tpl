{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product element
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<input type="hidden" name="{getNamePostedData(#participateSale#)}" value="0" />

<input
  type="checkbox"
  id="participate-sale"
  name="{getNamePostedData(#participateSale#)}"
  value="1"
  {if:product.getParticipateSale()}checked="checked"{end:} />

<label class="participate-sale" for="participate-sale">{t(#Product on sale#)}</label>

<div class="sale-discount-types">
  <widget
    class="\XLite\Module\CDev\Sale\View\SaleDiscountTypes"
    salePriceValue="{product.getSalePriceValue()}"
    discountType="{product.getDiscountType()}" />
</div>

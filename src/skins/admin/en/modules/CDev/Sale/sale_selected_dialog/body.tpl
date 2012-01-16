{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Set the sale price dialog. Products list popup dialog.
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<widget class="\XLite\Module\CDev\Sale\View\Form\SaleSelectedDialog" name="sale_selected_dialog_form" />

 <div class="set-price-dialog">

   <widget
     class="\XLite\Module\CDev\Sale\View\SaleDiscountTypes"
     salePriceValue="0"
     discountType="{%\XLite\Model\Product::SALE_DISCOUNT_TYPE_PRICE%}" />

   <div class="label">{t(#The changes will be applied to all selected products#)}</div>

   <widget class="\XLite\View\Button\Submit" label="Apply price" />

 </div>

<widget name="sale_selected_dialog_form" end />

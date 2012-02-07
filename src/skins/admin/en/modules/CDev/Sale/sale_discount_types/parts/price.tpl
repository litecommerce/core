{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product element
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="sale_discount_types", weight="10")
 *}

 <ul class="sale-discount sale-discount-price">
   <li class="discount-type">
     <input
         id="sale-price-sale-price"
         type="radio"
         name="{getNamePostedData(#discountType#)}"
         value="{%\XLite\Model\Product::SALE_DISCOUNT_TYPE_PRICE%}"
         {if:getParam(#discountType#)=%\XLite\Model\Product::SALE_DISCOUNT_TYPE_PRICE%}checked="checked"{end:} />
     <label for="sale-price-sale-price">
      {t(#Sale price#)}
     </label>
   </li>
   <li class="sale-price-value">
     <widget
       class="\XLite\View\FormField\Input\Text\Price"
       fieldOnly="true"
       mouseWheelIcon="false"
       fieldId="sale-price-value-{%\XLite\Model\Product::SALE_DISCOUNT_TYPE_PRICE%}"
       value="{getParam(#salePriceValue#)}" />
   </li>
 </ul>

 <div class="clear"></div>

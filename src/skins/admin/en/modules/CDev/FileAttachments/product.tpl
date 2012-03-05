{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product controller tab
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.10
 *}
<div class="product-attachments">

  <div class="add-file">
    <widget
      class="XLite\View\Button\FileSelector"
      label="Add file"
      object="product"
      objectId="{product.getProductId()}"
      fileObject="attachments" />
  </div>

  {if:product.attachments.count()}
  <widget class="XLite\Module\CDev\FileAttachments\View\Form\Attachments" product="{getProduct()}" name="product_attachments" />

    <ul class="files" IF="product.getAttachments()">
      <li FOREACH="product.getAttachments(),index,attachment" class="{getItemClass(attachment)}">

        <div class="row">
          <list name="product.attachments.row" attachment="{attachment}" />
        </div>

        <div class="info" style="display: none;">
          <list name="product.attachments.properties" attachment="{attachment}" />
        </div>

      </li>
    </ul>

    <widget class="XLite\Module\CDev\FileAttachments\View\Panel\Product" />

  <widget name="product_attachments" end />
  {end:}
</div>

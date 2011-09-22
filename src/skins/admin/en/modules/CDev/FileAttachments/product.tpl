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
      <li FOREACH="product.getAttachments(),index,attachment" class="attachment">

        <div class="row">
          <a href="#" class="move" title="{t(#Move#)}"><img src="images/spacer.gif" alt="" /></a>
          <input type="hidden" class="orderby" name="data[{attachment.getId()}][orderby]" value="{attachment.getOrderby()}" />

          <img src="images/spacer.gif" alt="" class="separator first-separator" />

          <img src="images/spacer.gif" alt="{t(attachment.storage.mimeName)}" class="mime-icon {attachment.storage.getMimeClass()}" />

          <a class="name" href="{attachment.storage.getURL()}">{attachment.storage.getFileName()}</a>
          <span IF="attachment.storage.getSize()" class="size">({formatSize(attachment.storage.getSize())})</span>

          <a href="{buildURL(#product#,#removeAttachment#,_ARRAY_(#product_id#^product.getProductId(),#id#^attachment.getId()))}" class="remove" title="{t(#Remove#)}"><img src="images/spacer.gif" alt="" /></a>

          <img src="images/spacer.gif" alt="" class="separator second-separator" />

          <div class="switcher">
            <img src="images/spacer.gif" alt="" />
            <a href="#">{t(#Properties#)}</a>
          </div>

        </div>

        <div class="info" style="display: none;">
          <table cellspacing="0" class="form">
            <tr class="title">
              <td class="label"><label for="attachmentName{attachment.getId()}">{t(#File title#)}</label></td>
              <td><input type="text" id="attachmentName{attachment.getId()}" name="data[{attachment.getId()}][title]" value="{attachment.getTitle()}" /></td>
            </tr>
            <tr class="description">
              <td class="label"><label for="attachmentDesc{attachment.getId()}">{t(#Description#)}</label></td>
              <td><textarea id="attachmentDesc{attachment.getId()}" name="data[{attachment.getId()}][description]">{attachment.getDescription()}</textarea></td>
            </tr>
          </table>
          <div class="reupload-file">
            <widget
              class="XLite\View\Button\FileSelector"
              style="reupload"
              label="Re-upload file"
              object="attachment"
              objectId="{attachment.getId()}"
              fileObject="attachments" />
          </div>
        </div>
      </li>
    </ul>

    <widget class="XLite\Module\CDev\FileAttachments\View\Panel\Product" />

  <widget name="product_attachments" end />
  {end:}
</div>

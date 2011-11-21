{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Attachments list
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.10
 *}
<div class="product-attachments">
  <ul>
    <li FOREACH="getAttachments(),attachment">
      <img src="images/spacer.gif" alt="{t(attachment.storage.mimeName)}" class="mime-icon {attachment.storage.getMimeClass()}" />
      <a href="{attachment.storage.getFrontURL()}">{attachment.getPublicTitle()}</a>
      <span IF="attachment.storage.getSize()" class="size">({formatSize(attachment.storage.getSize())})</span>
      <div IF="attachment.getDescription()"class="description">{attachment.getDescription()}</div>
    </li>
  </ul>
</div>

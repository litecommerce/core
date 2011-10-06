{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Deswcription
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.11
 *
 * @ListChild (list="product.attachments.form", weight="200", zone="admin")
 *}
<tr class="description">
  <td class="label"><label for="attachmentDesc{attachment.getId()}">{t(#Description#)}</label></td>
  <td><textarea id="attachmentDesc{attachment.getId()}" name="data[{attachment.getId()}][description]">{attachment.getDescription()}</textarea></td>
</tr>

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Title
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.11
 *
 * @ListChild (list="product.attachments.form", weight="100", zone="admin")
 *}
<tr class="title">
  <td class="label"><label for="attachmentName{attachment.getId()}">{t(#File title#)}</label></td>
  <td><input type="text" id="attachmentName{attachment.getId()}" name="data[{attachment.getId()}][title]" value="{attachment.getTitle()}" /></td>
</tr>

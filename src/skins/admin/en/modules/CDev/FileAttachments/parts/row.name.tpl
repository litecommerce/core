{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Name
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.11
 *
 * @ListChild (list="product.attachments.row", weight="400", zone="admin")
 *}
<a class="name" href="{attachment.storage.getURL()}">{attachment.storage.getFileName()}</a>
<span IF="attachment.storage.getSize()" class="size">({formatSize(attachment.storage.getSize())})</span>

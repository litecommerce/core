{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Title
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product.attachments.properties", weight="200", zone="admin")
 *}
<div class="reupload-file">
  <widget
    class="XLite\View\Button\FileSelector"
    style="reupload"
    label="Re-upload file"
    object="attachment"
    objectId="{attachment.getId()}"
    fileObject="attachments" />
</div>

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Image field
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

{if:getValue()}
  <div class="image"><img src="{value.getURL()}" alt="" /></div>
{else:}
  <div class="no-image">{t(#No image#)}</div>
{end:}

<widget
  class="\XLite\View\Button\FileSelector"
  label="{getButtonLabel()}"
  object="{getObject()}"
  objectId="{getObjectId()}"
  fileObject="{getFileObject()}"
  fileObjectId="{getFileObjectId()}" />

{if:isRemoveButtonVisible()}
  <input type="checkbox" name="imagesRemove[{getObject}][{getObjectId()}][{getFileObject()}][{getFileObjectId()}]" id="ir_{getObject}_{getObjectId()}_{getFileObject()}_{getFileObjectId()}" value="1" />
  <label for="ir_{getObject}_{getObjectId()}_{getFileObject()}_{getFileObjectId()}">{t(getRemoveButtonLabel())}</label>
{end:}

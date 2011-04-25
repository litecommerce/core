{**
 * Modules upload form
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<div class="upload-addons-text">
{t(#If you have a plugin in a .tar format, you may install it by uploading it here.#)}
</div>

<div class="upload-addon-form">

<form action="admin.php" method="post" name="uploadAddonsForm" enctype="multipart/form-data" onsubmit="javascript:this.submit();">
  <input type="hidden" name="target" value="upgrade" />
  <input type="hidden" name="action" value="upload_addon" />

  <div class="upload-area">
    <input type="file" name="modulePack" />
  </div>

  <div class="buttons">
  <widget class="\XLite\View\Button\Submit" label="{t(#Install add-on#)}" />
  </div>

</form>

</div>

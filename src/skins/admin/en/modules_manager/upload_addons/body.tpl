{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules upload form
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<div class="upload-addons-text">
  {t(#If you have a plugin in the .tar format, you can install it by uploading it here#)}.
</div>

<div class="upload-addon-form">

<form action="admin.php" method="post" name="uploadAddonsForm" enctype="multipart/form-data">
  <input type="hidden" name="target" value="upgrade" />
  <input type="hidden" name="action" value="upload_addon" />

  <div class="upload-area">
    <input type="file" name="modulePack" />
  </div>

  <div class="buttons">
  <widget class="\XLite\View\Button\Submit" style="main-button" label="{t(#Install add-on#)}" />
  </div>

</form>

</div>

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<div class="upload-addons-text">
{t(#If you have a plugin in a .phar format, you may install it by uploading it here.#)}
</div>


<div class="upload-addon-form">

<form action="admin.php" method="POST" name="uploadAddonsForm" enctype="multipart/form-data" onsubmit="javascript:this.submit();">

  <input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}" />

  <input type="hidden" name="action" value="upload" />

  <div class="upload-area">
    <input type="file" name="upload_addon[]" />
  </div>

  <a href="javascript:void(0);" class="add-more-upload-addons-images">{t(#Add one more add-on archive#)}</a>

  <div class="buttons">
  <widget class="\XLite\View\Button\Submit" label="{t(#Install add-ons#)}" />
  </div>

</form>

</div>

<script type="text/javascript">
//<![CDATA[
core.multiAdd('.upload-area', '.add-more-upload-addons-images', '<button type="button" class="remove">Remove</button>');
//]]>
</script>

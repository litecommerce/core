{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

{* :TODO: divide into parts *}

<div class="top-controls">

  <div class="form-panel addons-search-panel">

    <form name="search_form" method="GET" action="admin.php">
      <input FOREACH="getURLParams(),name,value" type="hidden" name="{name}" value="{value}" />

      <input IF="getParam(#substring#)" id="search_substring" type="text" name="substring" value="{getParam(#substring#)}" />
      <input IF="!getParam(#substring#)" class="default-value" id="search_substring" type="text" name="substring" value="{t(#Enter keywords#)}" />

      <widget class="\XLite\View\Button\Submit" label="{t(#Search#)}" />

      {* :TODO: move to CSS of course *}
      <div class="tags" style="float: right; border: 1px solid #000;">{t(#Tags#)}</div>

    </form>

{* :TODO: move it into a JS file *}
<script type="text/javascript">
var default_substring = '{t(#Enter keywords#)}';
var sObj = jQuery('#search_substring');
var sForm = jQuery('form[name=search_form]');
sObj.blur(function(e){
<!--
  if (jQuery(this).val() == '') {
    jQuery(this).addClass('default-value').val(default_substring);
  }
});
sObj.focus(function(e){
  if (jQuery(this).val() == default_substring) {
    jQuery(this).val('').removeClass('default-value');
  }
});
sForm.submit(function(e){
  if (sObj.val() == default_substring)
    sObj.val('');
})
-->
</script>

  </div>

  <div class="action-buttons">
    <widget class="\XLite\View\Button\UploadAddons" />
    <widget class="\XLite\View\Button\EnterLicenseKey" />
  </div>

</div>

<div class="clear"></div>

<widget template="{getBody()}" />

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
<div class="top-controls">

  <div class="form-panel addons-search-panel">

    <form action="admin.php" method="post" name="search_form" >
      <input type="hidden" name="target" value="addons_list" />
      <input type="hidden" name="action" value="search" />
        <input class="{if:mode=#featured#|!getCondition(#substring#)}default-value{end:}" id="search_substring" type="text" name="substring" value="{if:mode=#search#&getCondition(#substring#)}{getCondition(#substring#)}{else:}{t(#Enter keywords#)}{end:}" />
        <widget class="\XLite\View\Button\Submit" label="{t(#Search#)}" />
    </form>

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
    <widget class="\XLite\View\Button\Submit" label="{t(#Upload add-on...#)}" />
    <widget class="\XLite\View\Button\Submit" label="{t(#Enter license key...#)}" />
  </div>

</div>

<div class="clear"></div>

<div class="tags">
{*** TODO ***}
</div>

{* Display add-ons list *}
<widget class="\XLite\View\ItemsList\Module\Install" />

<div class="compatibility-note">
  <p>These modules are suitable for the current LiteCommerce version only!</p>
  <p>To see the list of all available modules, go to <a href="{%static::MARKETPLACE_URL%}">LC Marketplace</a></p>
</div>

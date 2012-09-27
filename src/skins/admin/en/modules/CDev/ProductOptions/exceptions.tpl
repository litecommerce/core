{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modify option groups exceptions
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<h3>{t(#Modify option groups exceptions#)}</h3>

<form action="admin.php" method="post" name="update_option_group_form" class="option-groups-exceptions-modify">
  <input type="hidden" name="target" value="product" />
  <input type="hidden" name="action" value="update_option_groups_exceptions" />
  <input type="hidden" name="page" value="product_options" />
  <input type="hidden" name="language" value="{language}" />
  <input type="hidden" name="id" value="{getProductId()}" />

  <div FOREACH="getExceptions(),eid,exception">
    <input type="checkbox" name="mark[]" value="{eid}" id="exception_mark_{eid}" class="mark" />
    <label for="exception_mark_{eid}" class="mark"><strong>{t(#Exception#)} #{eid}</strong></label>
    <widget template="modules/CDev/ProductOptions/exception.tpl" />
  </div>

  <div>
    <strong>{t(#Add new exception#)}</strong>
    <widget template="modules/CDev/ProductOptions/exception.tpl" eid="0" exception="{getEmptyException()}"/>
  </div>

  <div class="buttons">
    <widget class="\XLite\View\Button\Submit" label="{t(#Update exceptions#)}" />
    <widget IF="getExceptions()" class="\XLite\Module\CDev\ProductOptions\View\Button\DeleteSelectedExceptions" />
  </div>


</form>

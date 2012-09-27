{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Selector for Product classes
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<div IF="!isListEmpty()" class="select-classes">
  <widget template="form_field/select.tpl" />
{* TODO Rework
<div class="classes-list">
{getSelectedClassesList()}<a href="javascript:void(0);" class="popup-classes"></a>
</div>
*}
</div>
<span IF="isListEmpty()" class="empty-list">
  <a href="{buildURL(#product_classes#)}">{t(#Define classes#)}</a>
</span>

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * "Attributes" tab
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<widget class="XLite\View\Form\Product\Modify\Attributes" name="update_attributes_form" />
<widget class="XLite\View\Product\Details\Admin\Attributes" />
{foreach:product.getClasses(),class}
  {foreach:class.getAttributeGroups(),group}
    <widget class="XLite\View\Product\Details\Admin\Attributes" group="{group}" />
  {end:}
{end:}
  <div class="table-value">
  <widget
    class="\XLite\View\FormField\Input\Checkbox"
    fieldName="attrSepTab"
    fieldId="attrSepTab"
    fieldOnly="true"
    isChecked="{product.getAttrSepTab()}" />
    <label for="attrSepTab" class="separ-tab">{t(#Show product attributes in a separate tab#)}</label>
  </div>
  <div class="table-value">
  <widget class="\XLite\View\Button\Submit" label="{t(#Save#)}" style="main-button" />
  </div>
<widget name="update_attributes_form" end />

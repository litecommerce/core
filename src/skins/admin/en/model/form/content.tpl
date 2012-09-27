{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Form content
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

{* TODO (FlexyCompiler) - improve the approach to access array fields *}
{* TODO (FlexyCompiler) - add the ability to use constants *}

<div FOREACH="getFormFieldsForDisplay(),section,data" class="section {section}-section">
  <div class="header {section}-header" IF="{isShowSectionHeader(section)}">{data.sectionParamWidget.display()}</div>
  <ul class="table {section}-table">
    <li FOREACH="data.sectionParamFields,field" class="{getItemClass(fieldArrayPointer,fieldArraySize,field)}">
      {field.display()}
      {displayViewSubList(#field#,_ARRAY_(#section#^section,#field#^field))}
    </li>
  </ul>
</div>

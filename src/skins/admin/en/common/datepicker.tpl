{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Date picker
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<span class="date-picker-widget">
  {displayCommentedData(getDatePickerOptions())}
  <input type="text" name="{getParam(#field#)}" value="{getValueAsString()}" class="date-picker {getClassName()}" />
</span>

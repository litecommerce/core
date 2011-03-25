{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="order-status-selector{if:isStatusWarning()} status-warning{end:}">
  <select {getAttributesCode():h}>
    <option value="" IF="getParam(#allOption#)">{t(#All#)}</option>
    {foreach:getOptions(),optionValue,optionLabel}
      <option value="{optionValue}"{if:optionValue=getValue()} selected="selected"{end:}{if:isOptionDisabled(optionValue)} disabled="disabled"{end:}>{optionLabel:h}</option>
    {end:}
  </select>
  <a IF="isStatusWarning()" id="status_warning_{getParam(#orderId#)}" class="icon-warning popup-warning" href="#">
    <img src="images/spacer.gif" width="24" height="24" alt="" />
  </a>
  <div IF="isStatusWarning()" class="status-warning-content">
    {getStatusWarningContent()}
  </div>
</div>

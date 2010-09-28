{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Selected options 
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<ul class="selected-options">
  <li FOREACH="item.getOptions(),option">
    <span>{option.getActualName():h}:</span> {option.getActualValue():h}{if:!optionArrayPointer=optionArraySize}, {end:}
  </li>
</ul>

<div IF="getParam(#source#)" class="item-change-options">
  <a href="{getChangeOptionsLink()}" onclick="javascript: return changeOption.call(this);">{t(#Change options#)}</a>
</div>

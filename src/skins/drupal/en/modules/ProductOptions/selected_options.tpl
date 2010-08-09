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
<span class="item-option" FOREACH="item.getProductOptions(),option">
  {option.getActualName():h}: {option.getActualValue():h}<span IF="optionArrayPointer<optionArraySize">, </span>
</span>

<span IF="getParam(#source#)" class="item-change-options">
  <a href="{getChangeOptionsLink()}" onclick="javascript: return changeOption.call(this);">Change options</a>
</span>

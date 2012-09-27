{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Selected options
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<ul class="selected-options">
  {foreach:item.getOptions(),option}
    <li IF="!isOptionEmpty(option)">
      <span>{option.getActualName()}:</span>
      {option.getActualValue()}{if:!optionArrayPointer=optionArraySize}, {end:}
    </li>
  {end:}
</ul>

<div IF="getParam(#source#)" class="item-change-options">
  <a href="{getChangeOptionsLink()}">{t(#Change options#)}</a>
</div>

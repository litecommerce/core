{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Minicart row with item options
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<a class="item-options underline-emulation" href="{buildURL(#cart#)}" id="item-option{item.getItemId()}" rel="div.item-options.item-{item.getItemId()}"><span>{t(#options#)}</span></a>
<br />
<div class="internal-popup item-options item-{item.getItemId()}" style="display: none;">
  <ul class="item-options">
    <li FOREACH="getOptions(),option">{option.getActualName()}: {option.getActualValue()}</li>
  </ul>
</div>

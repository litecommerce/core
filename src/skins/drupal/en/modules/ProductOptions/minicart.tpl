{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Minicart row with item options
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<ul class="item-options">
  <li FOREACH="getOptions(),option">{option.getActualName():h}: {option.getActualValue():h}</li>
</ul>
{if:isLimitEnabled()}
  <a class="item-all-options" href="{buildUrl(#cart#)}" rel="ul.item-all-options.item-{getParam(#cartId#)}">all options</a>
  <br />
  <ul class="item-all-options item-{getParam(#cartId#)}" style="display: none;">
    <li FOREACH="getAllOptions(),option">{option.getActualName():h}: {option.getActualValue():h}</li>
  </ul>
{end:}

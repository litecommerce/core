{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Horizontal minicart items block
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="minicart.horizontal.childs", weight="10")
 *}
<div IF="!cart.isEmpty()" class="popup items-list">

  <p class="title">
    <a href="{buildURL(#cart#)}">{t(#X items in bag#,_ARRAY_(#count#^cart.countQuantity()))}</a>
  </p>

  <ul>
    <li FOREACH="getItemsList(),item">
      {displayViewListContent(#minicart.horizontal.item#,_ARRAY_(#item#^item))}
    </li>
  </ul>

  <p IF="isTruncated()" class="other-items"><a href="{buildURL(#cart#)}">{t(#Other items#)}</a></p>

  <hr />

  {displayViewListContent(#minicart.horizontal.buttons#)}

</div>

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Horizontal minicart items block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="minicart.horizontal.children", weight="10")
 *}
<div IF="!cart.isEmpty()" class="internal-popup items-list">

  <p class="title">
    <a href="{buildURL(#cart#)}">{t(#X items in bag#,_ARRAY_(#count#^cart.countQuantity()))}</a>
  </p>

  <ul>
    <li FOREACH="getItemsList(),item">
      <list name="minicart.horizontal.item" item="{item}" />
    </li>
  </ul>

  <p IF="isTruncated()" class="other-items"><a href="{buildURL(#cart#)}">{t(#Other items#)}</a></p>

  <hr />

  <list name="minicart.horizontal.buttons" />

</div>

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Gift certificate delivery methods block
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="giftcert.childs", weight="30")
 *}
<h3>Delivery method</h3>

{if:config.GiftCertificates.enablePostGC}

<ul class="delivery">
  <li FOREACH="getViewList(#giftcert.selectors#),w">
    {w.display()}
  </li>
</ul>

<hr class="tiny" />

{end:}

{displayViewListContent(#giftcert.methods#)}

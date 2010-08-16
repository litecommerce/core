{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<table class="selected-products">
  <tbody>
    <tr class="selected-product" FOREACH="cart.getItems(),cart_id,item">
      <td>
        <widget template="shopping_cart/item.tpl" />

        {* TODO: add method to replace the the "shopping_cart/item.tpl" template *}
        {* <widget module="GiftCertificates" template="modules/GiftCertificates/item.tpl" IF="item.gcid" />*}
      </td>
    </tr>
  </tbody>
</table>

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product details image box
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<table class="product-photo-box" IF="isViewListVisible(#product.details.page.image.photo#)">
  <tr>
    <td FOREACH="getViewList(#product.details.page.image.photo#),item">{item.display()}</td>
  </tr>
</table>

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="product.search.conditions", weight="30")
 *}

<tr>
  <td class="table-label">&nbsp;</td>
  <td style="width:10px;height:10px;">&nbsp;</td>
  <td class="table-input table-input-checkbox">

    <ul class="by-conditions">

      {displayViewListContent(#product.search.conditions.by-keywords#)}

    </ul>

  </td>
</tr>

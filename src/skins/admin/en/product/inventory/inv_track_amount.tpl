{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *
 * @ListChild (list="product.inventory.parts", weight="20")
 *}

<tr>
  <td>{t(#Quantity in stock#)}</td>
  <td>
    <input type="text" name="{getNamePostedData(#amount#)}" size="18" value="{inventory.getAmount()}" />
  </td>
</tr>

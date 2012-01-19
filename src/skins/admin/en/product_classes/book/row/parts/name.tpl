{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product class title
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.16
 *
 * @ListChild (list="productClasses.book.row", weight="100")
 *}

<div class="product-class-name">
  <input type="text" name="{getNamePostedData(#name#)}" value="{getClassName():h}" />
  <span IF="!isNew()">{getClassName()}</span>
</div>

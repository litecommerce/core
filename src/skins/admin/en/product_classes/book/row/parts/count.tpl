{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Attributes number
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.16
 *
 * @ListChild (list="productClasses.book.row", weight="500")
 *}

<div IF="!isNew()" class="product-class-number{if:!getAttributesNumber()} expandable{end:}">
  <div IF="!getAttributesNumber()" class="product-class-assign-attributes">{t(#Assign attributes#)}</div>
  <span>{getAttributesNumber()}</span>
</div>

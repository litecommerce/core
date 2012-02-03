{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Attribute type-specific data
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.16
 *
 * @ListChild (list="productClass.book.row.assignAttributes.attribute", weight="300")
 *}

<span class="additional-attr-info" IF="attribute.checkType(#Text#)">({t(#Text field#)})</span>

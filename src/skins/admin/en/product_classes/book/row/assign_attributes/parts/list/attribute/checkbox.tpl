{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Attribute checkbox
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.16
 *
 * @ListChild (list="productClass.book.row.assignAttributes.attribute", weight="100")
 *}

<input type="checkbox" name="{getNamePostedData(attribute.getId())}" value="1" checked="{isAttributeSelected(attribute)}" />

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Attributes list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.16
 *
 * @ListChild (list="product.attributes.parts", weight="100")
 *}

<div FOREACH="getAttributes(),data">
  <div>{getObjectField(getArrayField(data,#group#),#getTitle#)}</div>
  <div FOREACH="getArrayField(data,#attributes#),attribute">{displayNestedViewListContent(#fields#,_ARRAY_(#attribute#^attribute))}</div>
</div>

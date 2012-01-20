{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * "Checkbox" attribute
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.16
 *
 * @ListChild (list="product.attributes.parts.fields", weight="100")
 *}

<widget IF="attribute.checkType(#Boolean#)" class="\XLite\View\FormField\Input\Checkbox" label="{attribute.getTitle():h}" fieldName="{getNamePostedData(#value#,attribute.getId())}" isChecked="{attribute.getValue(getProduct())}" value="1" />

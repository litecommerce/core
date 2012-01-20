{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * "Number" attribute
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.16
 *
 * @ListChild (list="product.attributes.parts.fields", weight="100")
 *}

<widget IF="attribute.checkType(#Number#)" class="\XLite\View\FormField\Input\Text\Float" label="{attribute.getTitle():h}" fieldName="{getNamePostedData(#value#,attribute.getId())}" value="{attribute.getValue(getProduct())}" />

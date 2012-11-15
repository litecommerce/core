{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product attributes 
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product.details.common.product-attributes.attributes", weight="10")
 *}
<widget class="XLite\View\Product\Details\Customer\Attributes" />
{foreach:product.getClasses(),class}
  {foreach:class.getAttributeGroups(),group}
    <widget class="XLite\View\Product\Details\Customer\Attributes" group="{group}" />
  {end:}
{end:}

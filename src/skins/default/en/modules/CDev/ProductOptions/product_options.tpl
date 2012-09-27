{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product options
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<ul class="product-options">
  <li FOREACH="getOptions(),option" class="product-option">
    <strong class="subtitle">{option.getDisplayName()}</strong>
    <widget template="{getTemplateNameByOption(option)}" option="{option}" />
  </li>
</ul>

<widget template="modules/CDev/ProductOptions/options_exception.tpl" />

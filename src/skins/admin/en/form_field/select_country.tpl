{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Select country
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<select {getAttributesCode():h}>
  <option value="">{t(#Select one...#)}</option>
  <option FOREACH="getOptions(),optionValue" value="{optionValue.code:r}" selected="{isOptionSelected(optionValue)}">{optionValue.country:h}</option>
</select>

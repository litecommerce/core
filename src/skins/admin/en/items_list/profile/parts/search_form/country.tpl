{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * User country
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.profile.search.search_form", weight="400")
 *}

<tr>
  <td>{t(#Country#)}</td>
  <td>
    <widget
      class="\XLite\View\FormField\Select\Country"
      fieldName="country"
      fieldOnly=true
      stateSelectorId="stateSelectorId"
      stateInputId="stateBoxId"
      value="{getParam(#country#)}" />
  </td>
</tr>

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * User country state
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="itemsList.profile.search.search_form", weight="500")
 *}

<tr>
  <td>{t(#State#)}</td>
  <td>
    <widget class="\XLite\View\FormField\Select\State" fieldName="state" fieldOnly=true fieldId="stateSelectorId" value="{getParam(#state#)}" />
    <widget class="\XLite\View\FormField\Input\Text" fieldName="state" fieldOnly=true fieldId="stateBoxId" value="{getParam(#state#)}" />
  </td>
</tr>

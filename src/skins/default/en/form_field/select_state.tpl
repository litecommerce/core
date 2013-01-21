{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Select state
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<select id="{getFieldId()}" name="{getName()}"{getAttributesCode():h}>
  <option value="">{t(#Select one#)}</option>
  <option value="-1" selected="{getValue()=-1}">{t(#Other#)}</option>
  <option FOREACH="getOptions(),state" value="{state.getStateId():r}" selected="{state.getStateId()=getValue()}">{state.getState():h}</option>
</select>

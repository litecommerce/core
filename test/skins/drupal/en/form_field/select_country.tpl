{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Select state
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<select id="{getFieldId()}" name="{getName()}"{getAttributesCode():h} onchange="{onChange}" id="{fieldId}" size="1">
  <option value="">Select one..</option>
  <option FOREACH="getOptions(),optionValue" value="{optionValue.code:r}" selected="{optionValue.code=getValue()}">{optionValue.country:h}</option>
</select>


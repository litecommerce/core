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

<select name="{getName()}"{getAttributesCode():h} onchange="{onChange}" id="{fieldId}" size="1">
  <option value="">Select one..</option>
  <option FOREACH="getOptions(),optionValue,optionLabel" value="{optionValue.code:r}" selected="{optionValue.code=getValue()}">{optionLabel.country:h}</option>
</select>


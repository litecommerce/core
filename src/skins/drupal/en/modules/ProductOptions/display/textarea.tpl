{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Display text-absed product option as textareax
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<textarea {if:option.getCols()} cols="{option.getCols()}"{end:} {if:option.getRows()} rows="{option.getRows()}"{end:} name="product_options[{option.getGroupId()}]">{getOptionText(option)}</textarea>

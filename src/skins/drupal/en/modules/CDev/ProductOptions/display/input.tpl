{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Display text-based product option as input box
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<input type="text" name="product_options[{option.getGroupId()}]" value="{getOptionText(option)}"{if:option.getCols()} size="{option.getCols()}"{end:} class="text" />

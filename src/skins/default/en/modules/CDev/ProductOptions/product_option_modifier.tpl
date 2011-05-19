{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Price modifier for option
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
({foreach:getModifiers(),i,modifier}{if:!i=0}, {end:}<widget template="{getModifierTemplate(modifier)}" modifier="{modifier}" />{end:})

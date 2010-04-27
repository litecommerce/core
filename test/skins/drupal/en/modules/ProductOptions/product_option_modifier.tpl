{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Price modifier for option
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
({if:isShowPrice()}{if:option.absolute}{option.surcharge_sign} {price_format(optionGroup.modifiedPrice(option,1)):h}{else:}{option.surcharge_sign}{option.surcharge_abs}%{end:}{if:option.weight_percent|option.weight_absolute}, {end:}{end:}
{if:option.weight_percent}{option.weight_modifier_sign}{option.weight_modifier_abs}%{end:}
{if:option.weight_absolute}{option.weight_modifier_sign}{option.weight_modifier_abs} {config.General.weight_symbol}{end:})

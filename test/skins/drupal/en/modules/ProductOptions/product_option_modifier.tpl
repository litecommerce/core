{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
(<span IF="isShowPrice()"><span IF="option.absolute">{option.surcharge_sign} {price_format(optionGroup.modifiedPrice(option,1)):h}</span><span IF="option.percent">{option.surcharge_sign}{option.surcharge_abs}%</span><span IF="option.weight_percent|option.weight_absolute">, </span></span>
<span IF="option.weight_percent">{option.weight_modifier_sign}{option.weight_modifier_abs}%</span>
<span IF="option.weight_absolute">{option.weight_modifier_sign}{option.weight_modifier_abs} {config.General.weight_symbol}</span>)

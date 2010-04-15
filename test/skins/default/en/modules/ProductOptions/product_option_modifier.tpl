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
<span IF="opt.modifyParams">(
<span IF="!hidePrice">
<span IF="config.ProductOptions.absolute_price_format"> 
Price: {price_format(option.modifiedPrice(opt)):h}
</span>
<span IF="!config.ProductOptions.absolute_price_format">
<span IF="!opt.isZero">
<span IF="opt.absolute">Price: {opt.surcharge_sign} {price_format(option.modifiedPrice(opt,1)):h}</span>
<span IF="opt.percent">Price: {opt.surcharge_sign}{opt.surcharge_abs}%</span>
</span>
</span>
</span> {* hidePrice *}
<span IF="config.ProductOptions.absolute_weight_format&!option.modifiedWeight(opt)=#0#">
Weight: {option.modifiedWeight(opt)} {config.General.weight_symbol}
</span>
<span IF="!config.ProductOptions.absolute_weight_format">
<span IF="opt.weight_percent">Weight: {opt.weight_modifier_sign}{opt.weight_modifier_abs}%</span>
<span IF="opt.weight_absolute">Weight: {opt.weight_modifier_sign}{opt.weight_modifier_abs} {config.General.weight_symbol}</span>
</span>
)</span>

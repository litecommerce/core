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

<span IF="opt.modifyParams">
<span IF="opt.modifyParams">(
<span>
<span IF="!opt.isZero">
<span IF="opt.absolute">Price: {opt.surcharge_sign} {price_format(opt.surcharge_abs):h}</span>
<span IF="opt.percent">Price: {opt.surcharge_sign}{opt.surcharge_abs}%</span>
</span>
</span>
<span>
<span IF="opt.weight_percent">Weight: {opt.weight_modifier_sign}{opt.weight_modifier_abs}%</span>
<span IF="opt.weight_absolute">Weight: {opt.weight_modifier_sign}{opt.weight_modifier_abs} {config.General.weight_symbol}</span>
</span>
)</span>

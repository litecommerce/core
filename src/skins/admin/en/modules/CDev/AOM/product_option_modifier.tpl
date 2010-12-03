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

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
<script>
	function checkRiskFactor(id)
	{
        var risk_factor = document.getElementById(id);
        risk_factor = parseInt(risk_factor.value);
        if (isNaN(risk_factor)) {
            alert('Risk factor must be a number');
            return false;
        }
        return true;
	}
</script>
<tr>
	<td class=FormButton noWrap height=10>Risk factor: </td>
	<td><input type=checkbox name=show_factor checked="{show_factor}"> Show orders with risk factor higher than <input type=text id=risk_factor name=risk_factor size=3 value="{risk_factor}" onBlur= "javascript: checkRiskFactor('risk_factor'); "></td>
</tr>

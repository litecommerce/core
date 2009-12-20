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


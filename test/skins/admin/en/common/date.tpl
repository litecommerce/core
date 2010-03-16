<select name="{getField()}Month">
  <option FOREACH="getMonths(),k,v" value="{k}" selected="{v}">{getMonthString(k)}</option>
</select>

<select name="{getField()}Day">
  <option FOREACH="getDays(),k,v" value="{k}" selected="{v}">{k}</option>
</select>

<select name="{getField()}Year">
  <option FOREACH="getYears(),k,v" value="{k}" selected="{v}">{k}</option>
</select>

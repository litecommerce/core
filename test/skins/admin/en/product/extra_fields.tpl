
<!-- EXTRA FIELDS -->
{if:!target=#add_product#}
<tr FOREACH="extraFields,ef">
  <td valign="middle" class="FormButton">{ef.name:h}</td>
  <td><input type=text name="extra_fields[{ef.field_id}]" value="{ef.value:r}" size=45></td>
</tr>
{end:}
<!-- /EXTRA FIELDS -->


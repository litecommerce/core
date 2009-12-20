{* Product extra fields template *}
<tbody FOREACH="extraFields,ef">
<tr IF="!ef.value=##" valign=top>
    <td width="30%" class="ProductDetails">{ef.name:h}:</td>
    <td class="ProductDetails">{ef.value:h}</td>
</tr>
</tbody>


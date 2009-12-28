<table IF="widget.item.productOptions" border=0>
<tr>
    <td colspan=2>Selected options:</td>
</tr>
<tr FOREACH="widget.item.productOptions,option">
    <td>{option.class:h}:</td>
    <td>{option.option:h}</td>
</tr>
<tr>
</table>

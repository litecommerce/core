{if:option.name=#link_expires#}
<select name="{option.name}">
<option value="T" selected="{option.value=#T#}">Date</option>
<option value="D" selected="{option.value=#D#}">Downloads</option>
<option value="B" selected="{option.value=#B#}">Date and downloads</option>
</select>
{end:}

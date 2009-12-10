{if:option.isCheckbox()}
{if:option.name=#use_ftp#}
{if:!xlite.FTP_support}
<input id="{option.name}" type="checkbox" name="{option.name}" disabled>
<B>
<FONT color=red>(PHP doesn't support FTP functions)</FONT>
</B>
{else:}
<input id="{option.name}" type="checkbox" name="{option.name}" onClick="this.blur()" checked="{option.isChecked()}">
{end:}
{else:}
<input id="{option.name}" type="checkbox" name="{option.name}" onClick="this.blur()" checked="{option.isChecked()}">
{end:}
{end:}
{if:option.isText()}
{if:option.name=#ftp_password#}
<input id="{option.name}" type="password" name="{option.name}" value="{dialog.ftpPassword}" size=10>
{else:}
<input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size="{widget.dialog.getOptionSize(option.name)}">
{end:}
{end:}

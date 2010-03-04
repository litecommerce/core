{if:!state}
<font class="ValidateErrorMessage">&nbsp;&nbsp;&lt;&lt;&nbsp;Warning! CSV file was not specified.</font>
{else:}
    {if:state=#invalid_file#}<br /><font class="ValidateErrorMessage">&nbsp;&nbsp;Warning! The file you have specified in the 'File (CSV) local' field does not exist or cannot be read.<br /> &nbsp;&nbsp;Please enter the correct file location or set correct file permissions.
    </font>{end:}
    {if:state=#invalid_upload_file#}&nbsp;&nbsp;&lt;&lt;&nbsp;Warning! The file you have specified in the 'File (CSV) for upload' field has not been uploaded to the server.{end:}
    {if:state=#empty_file#}<br /><br /><font class="ValidateErrorMessage">&nbsp;&nbsp;Warning! CSV file was not specified.</font>{end:}
{end:}

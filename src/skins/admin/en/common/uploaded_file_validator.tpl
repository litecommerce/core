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
{if:!state}
<font class="ValidateErrorMessage">&nbsp;&nbsp;&lt;&lt;&nbsp;Warning! CSV file was not specified.</font>
{else:}
    {if:state=#invalid_file#}<br /><font class="ValidateErrorMessage">&nbsp;&nbsp;Warning! The file you have specified in the 'File (CSV) local' field does not exist or cannot be read.<br /> &nbsp;&nbsp;Please enter the correct file location or set correct file permissions.
    </font>{end:}
    {if:state=#invalid_upload_file#}&nbsp;&nbsp;&lt;&lt;&nbsp;Warning! The file you have specified in the 'File (CSV) for upload' field has not been uploaded to the server.{end:}
    {if:state=#empty_file#}<br /><br /><font class="ValidateErrorMessage">&nbsp;&nbsp;Warning! CSV file was not specified.</font>{end:}
{end:}

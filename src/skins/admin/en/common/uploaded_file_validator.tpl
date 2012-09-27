{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
{if:!state}
<span class="validate-error-message">&nbsp;&nbsp;&lt;&lt;&nbsp;{t(#Warning! CSV file was not specified.#)}</span>
{else:}
    {if:state=#invalid_file#}<br /><span class="validate-error-message">&nbsp;&nbsp;{t(#Warning! The file you have specified in the 'File (CSV) local' field does not exist or cannot be read.#)}<br /> &nbsp;&nbsp;{t(#Please enter the correct file location or set correct file permissions.#)}</span>
    {end:}
    {if:state=#invalid_upload_file#}&nbsp;&nbsp;&lt;&lt;&nbsp;{t(#Warning! The file you have specified in the 'File (CSV) for upload' field has not been uploaded to the server.#)}{end:}
    {if:state=#empty_file#}<br /><br /><span class="validate-error-message">&nbsp;&nbsp;{t(#Warning! CSV file was not specified.#)}{end:}</span>
{end:}

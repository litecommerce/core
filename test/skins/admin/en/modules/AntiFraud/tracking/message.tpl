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
{if:mode=#sent#}Message was sent successfully{end:}
{if:mode=#failed#}Message was NOT sent because your AntiFraud license key is expired {end:}
{if:response.result.error}AntiFraud service returns the following error : {response.result.error}{end:}
{if:response.data.check_error}AntiFraud service returns the following warning : {response.data.check_error}{end:}

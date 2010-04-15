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
<select name="{field}">
<option value="" IF="allOption">All</option>
<option value="Q" selected="{value=#Q#}">Queued</option>
<option value="P" selected="{value=#P#}">Processed</option>
<option value="I" selected="{value=#I#}">Incomplete</option>
<option value="F" selected="{value=#F#}">Failed</option>
<option value="D" selected="{value=#D#}">Declined</option>
<option value="C" selected="{value=#C#}">Complete</option>
</select>

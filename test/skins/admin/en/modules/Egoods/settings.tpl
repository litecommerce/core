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
{if:option.name=#link_expires#}
<select name="{option.name}">
<option value="T" selected="{option.value=#T#}">Date</option>
<option value="D" selected="{option.value=#D#}">Downloads</option>
<option value="B" selected="{option.value=#B#}">Date and downloads</option>
</select>
{end:}

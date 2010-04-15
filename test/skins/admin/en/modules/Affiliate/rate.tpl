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
<input type=text name="commission{itemID}" value="{pc.commission}" size=8 maxlength=12>&nbsp;
<select name="commission_type{itemID}">
    <option value="%" selected="{pc.commission_type=#%#}">%</option>
    <option value="$" selected="{pc.commission_type=#$#}">$</option>
</select>

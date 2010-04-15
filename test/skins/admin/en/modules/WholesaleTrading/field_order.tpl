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
{if:invalid_field_order}
        <font class=AdminHead>Field order:</font>&nbsp;
        <font class="ValidateErrorMessage">&lt;&lt;&nbsp;Duplicate field: &quot;{invalid_field_name}&quot;</font></td>
    {else:}
        <font class=AdminHead>Field order:</font></td>
    {end:}

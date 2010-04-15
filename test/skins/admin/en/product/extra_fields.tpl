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
<!-- EXTRA FIELDS -->
{if:!target=#add_product#}
<tr FOREACH="extraFields,ef">
  <td valign="middle" class="FormButton">{ef.name:h}</td>
  <td><input type=text name="extra_fields[{ef.field_id}]" value="{ef.value:r}" size=45></td>
</tr>
{end:}
<!-- /EXTRA FIELDS -->

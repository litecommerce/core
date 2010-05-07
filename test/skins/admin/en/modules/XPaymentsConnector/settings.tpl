{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Module settings
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
{if:option.isName(#xpc_status_part_refunded#)|option.isName(#xpc_status_refunded#)|option.isName(#xpc_status_declined#)|option.isName(#xpc_status_charged#)|option.isName(#xpc_status_auth#)|option.isName(#xpc_status_new#)}
  <select name="{option.name}">
    <option value="" selected="{option.value=##}">Do not change</option>
    <option value="I" selected="{option.value=#I#}">Not finished</option>
    <option value="Q" selected="{option.value=#Q#}">Queued</option>
    <option value="P" selected="{option.value=#P#}">Processed</option>
    <option value="F" selected="{option.value=#F#}">Failed</option>
  </select>
{end:}

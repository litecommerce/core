{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order status selector
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<select name="{field}" {if:xlite.GoogleCheckoutEnabled&googleCheckoutOrder} disabled="disabled"{end:}>
  <option value="" IF="allOption">{t(#All#)}</option>
  <option value="Q" selected="{getParam(#value#)=#Q#}">{t(#Queued#)}</option>
  <option value="P" selected="{getParam(#value#)=#P#}">{t(#Processed#)}</option>
  <option value="I" selected="{getParam(#value#)=#I#}">{t(#Incomplete#)}</option>
  <option value="F" selected="{getParam(#value#)=#F#}">{t(#Failed#)}</option>
  <option value="D" selected="{getParam(#value#)=#D#}">{t(#Declined#)}</option>
  <option value="C" selected="{getParam(#value#)=#C#}">{t(#Complete#)}</option>
</select>

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Help desk form
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<form action="{buildUrl(#helpdesk#,#send#)}" method="post" name="helpdesk_form" class="helpdesk" style="display: none;">
  <h2>Get help</h2>
  <div>
    <label for="hd_subject">Subject:</label>
    <input id="hd_subject" type="text" name="subject" value="" />
  </div>
  <div>
    <label for="hd_message">Message:</label>
    <textarea id="hd_message" name="message" rows="3" cols="60"></textarea>
  </div>
  <input type="submit" value="Send" class="submit" />
</form>
{foreach:getCSSFiles(),src}
<link rel="stylesheet" type="text/css" href="skins/admin/en/{src}" />
{end:}

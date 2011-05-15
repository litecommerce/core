{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * List of status messages
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
 
<div class="service-messages-section">
  <div class="ready-to-install-service-message">
    <div FOREACH="getMessages(),entryName,messageList">
      {foreach:messageList,message}
      {* :NOTE: do not add t(##) here: messages are already translated *}
      <div class="message-entry">{message}</div>
      {end:}
    </div>
  </div>
</div>

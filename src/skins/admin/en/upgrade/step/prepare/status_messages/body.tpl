{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * List of status messages
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

{* :TODO: merge with the "admin/en/upgrade/step/ready_to_install/status_messages/body.tpl" *}

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

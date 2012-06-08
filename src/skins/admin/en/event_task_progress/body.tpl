{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Event task progress bar
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.22
 *}

<div class="progress-bar {if:isBlockingDriver()}blocking{else:}noblocking{end:}" data-event="{getEvent()}">

  <h3 IF="getEventTitle()">{getEventTitle()}</h3>
  <div class="bar" data-percent="{getPercent()}" title="{getPercent()}%"></div>
  {if:isBlockingDriver()}
    <p class="note">{t(#Refresh the page to update the status#)}</p>
  {else:}
    <p class="note">{t(#Wait#)}</p>
  {end:}

</div>

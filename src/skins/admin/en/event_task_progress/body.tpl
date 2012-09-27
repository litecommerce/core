{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Event task progress bar
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<div class="progress-bar {if:isBlockingDriver()}blocking{else:}noblocking{end:}" data-event="{getEvent()}">

  <h3 IF="getEventTitle()">{getEventTitle()}</h3>
  <div class="bar" data-percent="{getPercent()}" title="{getPercent()}%"></div>
  {if:isBlockingDriver()}
    <p IF="getBlockingNote()" class="note">{getBlockingNote()}</p>
  {else:}
    <p if="getNonBlockingNote()" class="note">{getNonBlockingNote()}</p>
  {end:}

</div>

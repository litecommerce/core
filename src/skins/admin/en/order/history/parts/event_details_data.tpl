{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order history event date
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @ListChild (list="order.history.base.events.details.info", weight="30")
 *}
<div class="order-event-details" IF="getDetails(event)">
  <div class="details">
    <ul>
      <li FOREACH="getDetails(event),columnId,columnData" class="order-history-object-detail-column">
        <ul>
          <li FOREACH="columnData,cell_id,cell">
            <span class="label">{cell.getName()}:</span> <span class="value">{cell.getValue()}</span>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</div>

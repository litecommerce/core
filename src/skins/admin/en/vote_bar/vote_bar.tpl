{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Vote bar
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<div class="vote-bar">
  <ul>
    {foreach:getStars(),level,star}
      <li class="star-{level}">
        <span{if:star.full} class="full"{end:}>
        {if:star.percent}
          <img src="images/spacer.gif" alt="" style="width: {star.percent}%;" />
        {end:}
        </span>
      </li>
    {end:}
  </ul>
</div>

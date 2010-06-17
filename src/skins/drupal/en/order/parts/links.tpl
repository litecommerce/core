{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order buttons / links panel
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="order.childs", weight="20")
 *}
<div class="order-buttons">
  {foreach:getViewList(#order.links#),i,w}
    {if:!i=0} | {end:}
    {w.display()}
  {end:}
</div>

<hr class="tiny" />

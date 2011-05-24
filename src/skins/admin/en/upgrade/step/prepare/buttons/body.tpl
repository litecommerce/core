{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Buttons
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<form action="admin.php" method="post">
  <input type="hidden" name="target" value="upgrade" />
  <input type="hidden" name="action" value="download" />
  <input type="hidden" name="mode"   value="download_updates" />

  <widget class="\XLite\View\Upgrade\Step\Prepare\IncompatibleEntries" />

  <div class="incompatible-list-actions">
    {displayInheritedViewListContent(#sections#)}
    <div class="clear"></div>
  </div>
</form>

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * "Upgrade core" link
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="top_links.version_notes", weight="300")
 *}

<li IF="isCoreUpgradeAvailable()&!areUpdatesAvailable()" class="upgrade-note">
  <widget class="\XLite\View\Upgrade\SelectCoreVersion\Link" />
</li>

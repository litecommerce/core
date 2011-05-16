{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Backup mesaage
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="upgrade.step.completed.backup.sections", weight="100")
 *}

{* :NOTE: message is already translated *}
<div class="upgrade-note upgrade-description">
  {t(#The upgrade is completed. Please, do not close this page until you check your web site and check that everything works properly#)}.
</div>

<widget class="\XLite\View\Button\Link" style="main-button" label="Open storefront" blank="1" location={getShopURL()} />

<div class="upgrade-note upgrade-description">
  {t(#If there are some critical errors occured you can do the following#)}:
</div>

{displayInheritedViewListContent(#actions#)}

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules main section list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *
 * @ListChild (list="itemsList.module.install.columns.module-main-section", weight="100")
 *}

<div IF="!isInstalled(module)">
  <div IF="isFree(module)">{t(#Free#)}</div>
  <div IF="!isFree(module)">{formatPrice(module.getPrice(),module.getCurrency()):r} <span IF="isPurchased(module)">{t(#Purchased#)}</span></div>
</div>

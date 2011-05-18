{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Upgrade entry name
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="upgrade.install_updates.sections.form.info", weight="100")
 *}

<li class="name" IF="isModule(entry)">{entry.getName()}</li>
<li class="name core" IF="!isModule(entry)">{entry.getName()}</li>

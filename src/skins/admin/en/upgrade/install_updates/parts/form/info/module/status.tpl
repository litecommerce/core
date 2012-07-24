{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Module status
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="upgrade.install_updates.sections.form.info.module", weight="200")
 *}

<li class="enabled" IF="entry.isEnabled()">{t(#enabled#)}</li>
<li class="disabled" IF="!entry.isEnabled()">{t(#now disabled#)}</li>

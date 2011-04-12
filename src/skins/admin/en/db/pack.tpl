{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * 'Pack distributivee' tab template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}


This section is available because of developer mode enabled in the configuration file.
By clicking on the button below you will get an archive containing source code of your store.

<br />
<br />

<form action="admin.php" method="post" id="dbform" enctype="multipart/form-data">

  <input type="hidden" name="target" value="pack_distr" />
  <input type="hidden" name="action" value="pack_distr" />

  <widget class="\XLite\View\Button\Regular" label="Pack distributive" style="main-button" jsCode="submitFormDefault(this.form, 'pack_distr');" />

</form>

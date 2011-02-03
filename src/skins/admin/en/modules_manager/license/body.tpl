{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<div class="module-license">

  <div class="form">
    <form action="admin.php" method="post" name="search_form" >
      <input type="hidden" name="target" value="module_installation" />
      <input type="hidden" name="action" value="get_package" />
      <input type="hidden" name="module_id" value="{getModuleId()}" />

      <widget class="\XLite\View\Button\Submit" label="{t(#Install add-on#)}" />
    </form>
  </div>

  <div class="license-text">
    <pre>
      {getLicense()}
    </pre>
  </div>

</div>


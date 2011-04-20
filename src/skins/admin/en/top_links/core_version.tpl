{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Core version
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<span class="current">{t(#v.#)} {getCurrentCoreVersion()}</span>

<span IF="isCoreUpgradeAvailable()" class="upgrade-note">
  {* :TODO: this link must open the popup to select core version *}
  <a href="{buildURL(#upgrade#,##,_ARRAY_(#version#^##))}">{t(#Upgrade available#)}</a>
</span>

<span IF="araUpdatesAvailable()&!isCoreUpgradeAvailable()" class="updates-note">
  <a href="{buildURL(#upgrade#,##,_ARRAY_(#mode#^#install_updates#))}">{t(#Updates available#)}</a>
</span>

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

<span IF="araUpdatesAvailable()" class="updates-note">
  <a href="{buildURL(#upgrade#,##,_ARRAY_(#mode#^#install_updates#))}" title="{t(#There are updates for installed modules and/or LC core#)}">{t(#Updates available#)}</a>
</span>

<span IF="isCoreUpgradeAvailable()&!araUpdatesAvailable()" class="upgrade-note">
  {* :TODO: this link must open the popup to select core version *}
  <a href="{buildURL(#upgrade#,##,_ARRAY_(#version#^##))}" title="{t(#Upgrade for LC core is available#)}">{t(#Upgrade available#)}</a>
</span>


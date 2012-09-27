{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * "Updates are available" link
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="top_links.version_notes", weight="200")
 *}

<li IF="areUpdatesAvailable()" class="updates-note">
  <a href="{buildURL(#upgrade#,##,_ARRAY_(#mode#^#install_updates#))}" title="{t(#Updates for the LC core and/or installed modules are available#)}">
    {t(#Updates are available#)}
  </a>
</li>

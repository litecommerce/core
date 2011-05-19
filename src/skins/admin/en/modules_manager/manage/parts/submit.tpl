{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Submit button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="modules.manage.buttons", weight="0")
 *}

 {* TODO: rework design to make it more flexy *}

<widget class="\XLite\View\Button\Regular" label="{t(#Add new add-ons#)}" style="main-button" jsCode="self.location.replace('{buildURL(#addons_list_marketplace#)}')" />

<widget class="\XLite\View\Button\Addon\Upload" style="main-button" />
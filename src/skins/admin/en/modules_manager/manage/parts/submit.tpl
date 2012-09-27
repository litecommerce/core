{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Submit button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="modules.manage.buttons", weight="0")
 *}

 {* TODO: rework design to make it more flexy *}

<widget class="\XLite\View\Button\Link" label="{t(#More add-ons#)}" style="main-button" location="{buildURL(#addons_list_marketplace#)}" />

<widget class="\XLite\View\Button\Addon\Upload" style="main-button" />

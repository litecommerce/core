{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * The "Try again" button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="upgrade.step.ready_to_install.buttons.sections", weight="200")
 *}

<widget IF="!isNextStepAvailable()" class="\XLite\View\Button\Link" label="{t(#Check again#)}" style="main-button" location="{buildURL(#upgrade#,#check_integrity#)}" />

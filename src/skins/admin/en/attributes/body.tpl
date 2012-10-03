{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Attributes page template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<widget class="\XLite\View\Button\Regular" style="new-attribute" label="{t(#New attribute#)}" jsCode="popup_attribute({productClass.getId()})" />
<widget class="\XLite\View\Button\Regular" style="manage-groups" label="{t(#Manage groups#)}" jsCode="popup_attribute_groups({productClass.getId()})" />

<br />
<br />

<widget template="common/dialog.tpl" body="attributes/list.tpl" />

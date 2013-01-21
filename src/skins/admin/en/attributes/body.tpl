{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Attributes page template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<div class="buttons" data-class-id="{productClass.getId()}" />
<widget class="\XLite\View\Button\Submit" style="new-attribute" label="{t(#New attribute#)}" />
<widget class="\XLite\View\Button\Submit" style="manage-groups" label="{t(#Manage groups#)}" />
</div>

{if:isListVisible()}
<widget template="common/dialog.tpl" body="attributes/list.tpl" />
{else:}
{t(#No attributes are defined for the product class yet.#)}
{end:}

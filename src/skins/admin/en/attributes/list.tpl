{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Attributes list table template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<widget class="XLite\View\Form\ItemsList\Attribute\Table" name="list" />
<widget class="XLite\View\ItemsList\Model\Attribute" />
{foreach:getAttributeGroups(),group}
  <widget class="XLite\View\ItemsList\Model\Attribute" group="{group}" />
{end:}
<widget name="list" end />

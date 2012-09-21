{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Attribute groups list table template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<widget class="XLite\View\Form\ItemsList\AttributeGroup\Table" name="list" />
  <widget class="XLite\View\ItemsList\Model\AttributeGroup" />
  <div class="button submit">
    <widget class="\XLite\View\Button\Submit" label="{t(#Apply changes#)}" style="main-button" name="submit" />
  </div>
<widget name="list" end />

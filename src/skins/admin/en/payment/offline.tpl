{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Offline method common configuration page
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<ul class="table">
  <li><widget class="XLite\View\FormField\Input\Text" fieldName="properties[name]" label="{t(#Name#)}" required="true" value="{paymentMethod.getName()}" /></li>
  <li><widget class="XLite\View\FormField\Textarea\Simple" fieldName="properties[instruction]" label="{t(#Payment instructions#)}" value="{paymentMethod.getInstruction()}" help="{t(#These instructions will appear below the order invoice on the page which customers see after they confirm their order.#)}" /></li>
</ul>

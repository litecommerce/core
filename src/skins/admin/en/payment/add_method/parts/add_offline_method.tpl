{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Add offline method
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="payment.method.add.offline")
 *}

<widget class="XLite\View\Form\Payment\Method\Admin\AddOfflineMethod" name="add_offline_method" className="add-offline-method validationEngine" />

  <ul class="table">
    <li><widget class="XLite\View\FormField\Input\Text" fieldName="name" label="{t(#Name#)}" required="true" /></li>
    <li><widget class="XLite\View\FormField\Textarea\Simple" fieldName="instruction" label="{t(#Payment instructions#)}" comment="{t(#These instructions will appear below the order invoice on the page which customers see after they confirm their order.#)}" /></li>
  </ul>

  <widget class="XLite\View\Button\Submit" label="{t(#Add#)}" style="action" />

<widget name="add_offline_method" end />

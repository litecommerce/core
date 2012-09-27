{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment methods list : line : configure button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="payment.methods.list.line", weight=300)
 *}

<widget IF="isSeparateConfigureButtonVisible(method)" class="XLite\View\Button\Link" label="{t(#Configure#)}" location="{method.getConfigurationURL()}" style="configure"/>

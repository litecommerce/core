{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Orders search page
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<widget IF="isSearchVisible()" template="common/dialog.tpl" name="searchOrdersForm" body="order/search_form.tpl" />

<widget class="\XLite\View\Form\ItemsList\Order\Main" name="orders_form" />
  <widget class="\XLite\View\ItemsList\Model\Order\Admin\Search" />
<widget name="orders_form" end />

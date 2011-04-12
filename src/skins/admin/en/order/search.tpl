{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<widget template="common/dialog.tpl" name="searchOrdersForm" body="order/search_form.tpl" />

{* Open <form ...> tag *}
<widget class="\XLite\View\Form\Order\Modify\Batch" name="orders_form" />

  {* List of orders *}
  <widget class="\XLite\View\ItemsList\Order\Admin\Search" />

{* Close </form> tag *}
<widget name="orders_form" end />


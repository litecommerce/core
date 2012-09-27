{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Add/Modify category template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<p>{t(#Mandatory fields are marked with an asterisk#)} (<span class="star">*</span>).<br /><br />

<widget class="XLite\View\Form\Category\Modify\Single" name="modify_form" />

<table title="{t(#Category modify form#)}">
  <list name="category.modify.list" />
</table>

<widget name="modify_form" end />

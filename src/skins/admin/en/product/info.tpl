{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * TODO: the View\Model should be used instead
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<p>{t(#Mandatory fields are marked with an asterisk#)} (<span class="star">*</span>).<br /><br />

<widget class="XLite\View\Form\Product\Modify\Single" name="modify_form" className="product-modify-form" />

<table class="product-list">

<tr>
  <td class="name-attribute">&nbsp;</td>
  <td class="star">&nbsp;</td>
  <td class="value-attribute">&nbsp;</td>
</tr>

<list name="product.modify.list" />

</table>

<widget name="modify_form" end />

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * TODO: the View\Model should be used instead
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<p>{t(#Mandatory fields are marked with an asterisk#)} (<span class="star">*</span>).<br /><br />

<widget class="XLite\View\Form\Product\Modify\Single" name="modify_form" />

<table cellpadding="0">

<tr>
  <td style="width:30%;">&nbsp;</td>
  <td>&nbsp;</td>
</tr>

{displayViewListContent(#product.modify.list#)}

</table>

<widget name="modify_form" end />

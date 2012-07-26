{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category membership
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="category.modify.list", weight="500")
 *}

<tr IF="!isRoot()">
  <td>{t(#Membership#)}</td>
  <td class="star"></td>
  <td>
    <widget
      IF="!category.getMembership()"
      class="\XLite\View\MembershipSelect"
      template="common/select_membership.tpl"
      field="{getNamePostedData(#membership#)}"
      value="0" />
    <widget
      IF="category.getMembership()"
      class="\XLite\View\MembershipSelect"
      template="common/select_membership.tpl"
      field="{getNamePostedData(#membership#)}"
      value="{category.membership.getMembershipId()}" />
  </td>
</tr>

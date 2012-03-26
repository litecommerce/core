{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category membership
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="category.modify.list", weight="50")
 *}

<tr IF="!getRootCategoryId()=category.getCategoryId()">
  <td>{t(#Membership#)}</td>
  <td class="star">*</td>
  <td>
    <widget class="\XLite\View\MembershipSelect" template="common/select_membership.tpl" field="membership_id" value="{category.membership}" />
  </td>
</tr>

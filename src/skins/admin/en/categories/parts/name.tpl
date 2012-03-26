{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category name
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="category.modify.list", weight="10")
 *}

<tr>
  <td>{t(#Category name#)}</td>
  <td class="star">*</td>
  <td>
    <input type="text" name="name" value="{category.name}" size="50" maxlength="255" />
    &nbsp;<span IF="!valid" class="validate-error-message">&lt;&lt; {t(#Required field#)}</span>
  </td>
</tr>

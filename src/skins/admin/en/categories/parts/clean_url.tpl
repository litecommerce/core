{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category clean url
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="category.modify.list", weight="100")
 *}

<tr IF="!getRootCategoryId()=category.getCategoryId()">
  <td>{t(#Clean URL#)}</td>
  <td>&nbsp;</td>
  <td><input type="text" name="clean_url" value="{category.clean_url}" size="50" /></td>
</tr>

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category image
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.21
 *
 * @ListChild (list="category.modify.list", weight="400")
 *}

<tr IF="hasImage()">
  <td>{t(#Image#)}</td>
  <td class="star"></td>
  <td>
    <img IF="category.hasImage()" src="{category.image.getURL()}" alt="" />
    <img IF="!category.hasImage()" src="images/no_image.png" alt="" />
    <br />
    <widget class="\XLite\View\Button\FileSelector" label="Image upload" object="category" objectId="{category.getCategoryId()}" fileObject="image" />
  </td>
</tr>

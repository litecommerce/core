{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Images 
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<div class="items-list-table">
<table class="list" cellspacing="0">

  <thead>
    <tr>
      <th class="delete">{t(#Delete#)}</th>
      <th colspan="2" class="image">{t(#Image#)}</th>
    </tr>
  </thead>

  <tbody class="lines">
    <tr FOREACH="getImages(),image">
      <th>
        <widget class="\XLite\View\FormField\Input\Checkbox" fieldName="delete[{image}]" fieldOnly=1 />
      </th>
      <td><img src="{getImageUrl(image)}" alt="" /></td>
      <td class="main">images/{image}</td>
    </tr>
  </tbody>

</table>
</div>

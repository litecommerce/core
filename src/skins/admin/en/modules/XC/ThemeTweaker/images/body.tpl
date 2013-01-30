{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Images 
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

{if:getImages()}
<div class="items-list-table items-list">
  <table class="list" cellspacing="0">
    <thead>
      <tr>
        <th>{t(#Image#)}</th>
        <th>{t(#Path for using in custom CSS#)}</th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody class="lines">
      <tr FOREACH="getImages(),image" class="line">
        <td class="image"><a href="{getImageUrl(image)}" target="_blank"><img src="{getImageUrl(image)}" alt="" /></a></td>
        <td class="main">images/{image}</td>
        <td class="actions right">
          <div class="separator"></div>
          <div class="action">
            <widget class="XLite\View\Button\Remove" buttonName="delete[{image}]" />
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</div>
{else:}
<div class="no-images">{t(#No images uploaded#)}</div>
{end:}

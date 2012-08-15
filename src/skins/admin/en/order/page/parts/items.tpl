{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order : items
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="order.items", weight="100")
 *}

<table cellspacing="0" class="items">

  <thead>
    <tr><list name="order.items.head" /></tr>
    <tr class="last-row"><list name="order.items.subhead" /></tr>
  </thead>

  <tbody>

    {foreach:order.getItems(),index,item}
      <tr><list name="order.items.item" item="{item}" /></tr>
      <tr><list name="order.items.subitem" item="{item}" /></tr>
    {end:}

    <tr FOREACH="getViewList(#order.items.items#),w">
      {w.display()}
    </tr>

  </tbody>

</table>

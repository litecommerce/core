{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * "Options" part of the search form
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 *
 * @ListChild (list="itemsList.product.search.form.options", weight="100")
 *}

{*displayViewListContent(#itemsList.product.search.form.options#)*}

  <table class="advanced-search-options">

      <tr>

        <td class="option-name title-search-by-options">
          {t(#Search in#)}:
        </td>

        <td>

          <ul class="search-by-options">

            <li><label for="by-title">
              <input IF="!#Y#=getParam(#by_title#)" type="checkbox" name="by_title" id="by-title" value="Y" />
              <input IF="#Y#=getParam(#by_title#)" type="checkbox" name="by_title" id="by-title" value="Y" checked="checked" />
              {t(#Title#)}
            </label></li>

            <li><label for="by-descr">
              <input IF="!#Y#=getParam(#by_descr#)" type="checkbox" name="by_descr" id="by-descr" value="Y" />
              <input IF="#Y#=getParam(#by_descr#)" type="checkbox" name="by_descr" id="by-descr" value="Y" checked="checked" />
              {t(#Description#)}
            </label></li>

            <li><label for="by-sku">
              <input IF="!#Y#=getParam(#by_sku#)" type="checkbox" name="by_sku" id="by-sku" value="Y" />
              <input IF="#Y#=getParam(#by_sku#)" type="checkbox" name="by_sku" id="by-sku" value="Y" checked="checked" />
              {t(#SKU#)}
            </label></li>

          </ul>

        </td>
      </tr>

      <tr>
        <td class="option-name title-category">
          {t(#Category#)}:
        </td>
        <td>
          <widget class="\XLite\View\CategorySelect" fieldName="categoryId" selectedCategoryIds="{_ARRAY_(getParam(#categoryId#))}" allOption />
        </td>
      </tr>

    </table>

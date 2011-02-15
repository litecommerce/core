<widget class="\XLite\Module\CDev\FeaturedProducts\View\Form\Search" name="search_form" />

  <table cellpadding="3" cellspacing="1">

      <tr>
        <td class="table-label">Product SKU</td>
        <td style="width:10px;height:10px;"></td>
        <td style="height:10px;"><input type="text" size="6" name="sku" value="{getCondition(#sku#):r}"></td>
      </tr>

      <tr>
        <td class="table-label">Product Title</td>
        <td style="width:10px;height:10px;"></td>
        <td style="height:10px;"><input type="text" size="30" name="substring" value="{getCondition(#substring#):r}"></td>
      </tr>

      <tr>
        <td class="table-label">In category</td>
        <td style="width:10px;height:10px;"><span class="error-message">*</span></td>
        <td style="height:10px;">
          <widget class="\XLite\View\CategorySelect" fieldName="categoryId" selectedCategoryId="{getCondition(#categoryId#):r}" allOption />
        </td>
      </tr>

      <tr>
        <td class="table-label" colspan="3">
          Search in subcategories
          <input type="checkbox" name="searchInSubcats" checked="{getCondition(#searchInSubcats#)|!mode=#search_featured_products#}" value="1">
        </td>
      </tr>

      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>

      <tr>
        <td colspan="3"><widget class="\XLite\View\Button\Submit" label="Search" /></td>
      </tr>

  </table>

<widget name="search_form" end />

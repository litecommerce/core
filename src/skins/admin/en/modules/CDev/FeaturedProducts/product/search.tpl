<widget class="\XLite\Module\CDev\FeaturedProducts\View\Form\Search" name="search_form" />

  <table border="0" cellpadding="3" cellspacing="1">

      <tr>
        <td class="FormButton" nowrap="nowrap" height="10">Product SKU</td>
        <td width="10" height="10"></td>
        <td height="10"><input size="6" name="sku" value="{getCondition(#sku#):r}"></td>
      </tr>

      <tr>
        <td class="FormButton" nowrap="nowrap" height="10">Product Title</td>
        <td width="10" height="10"></td>
        <td height="10"><input size="30" name="substring" value="{getCondition(#substring#):r}"></td>
      </tr>

      <tr>
        <td class="FormButton" nowrap="nowrap" height="10">In category</td>
        <td width="10" height="10"><font class="ErrorMessage">*</font></td>
        <td height="10">
          <widget class="\XLite\View\CategorySelect" fieldName="categoryId" selectedCategoryId="{getCondition(#categoryId#):r}" allOption />
        </td>
      </tr>

      <tr>
        <td class="FormButton" nowrap="nowrap" height="10" colspan="3">
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

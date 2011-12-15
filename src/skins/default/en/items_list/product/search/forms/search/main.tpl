{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Main part of the search form
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 *
 * @ListChild (list="itemsList.product.search.form.main", weight="100")
 *}

{*displayViewListContent(#itemsList.product.search.form.main#)*}

  <table class="search-form-main-part">

      <tr>

        <td class="substring-cell">
          <input type="text" class="form-text" size="30" maxlength="200" name="substring" value="{getParam(#substring#)}" />
        </td>

        <td>
          <widget class="\XLite\View\Button\Submit" label="Search products" style="search-form-submit" />
        </td>

      </tr>

      <tr class="including-options-list">

        <td>

          <ul class="search-including-options">

            <li>
              <input IF="!#all#=getParam(#including#)" type="radio" name="including" id="including-all" value="all" />
              <input IF="#all#=getParam(#including#)" type="radio" name="including" id="including-all" value="all" checked="checked" />
              <label for="including-all">{t(#All words#)}<label/>
            </li>

            <li>
              <input IF="!#any#=getParam(#including#)" type="radio" name="including" id="including-any" value="any" />
              <input IF="#any#=getParam(#including#)" type="radio" name="including" id="including-any" value="any" checked="checked" />
              <label for="including-any">{t(#Any word#)}</label>
            </li>

            <li>
              <input IF="!#phrase#=getParam(#including#)" type="radio" name="including" id="including-phrase" value="phrase" />
              <input IF="#phrase#=getParam(#including#)" type="radio" name="including" id="including-phrase" value="phrase" checked="checked" />
              <label for="including-phrase">{t(#Exact phrase#)}</label>
            </li>

          </ul>

        </td>

        <td class="less-search-options-cell">
          <a href="javascript:void(0);" onclick="javascript:core.toggleText(this,'Less search options','#advanced_search_options');">{t(#More search options#)}</a>
        </td>

      </tr>

    </table>

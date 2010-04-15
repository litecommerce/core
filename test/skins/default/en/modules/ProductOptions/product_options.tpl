{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<tr id="optionsValidator">
<td colspan=2>&nbsp;
<widget template="modules/ProductOptions/options_validation_js.tpl">
</td>
</tr>

<tr id="optionsTitle"><td class="ProductDetailsTitle" colspan=2>Options</td></tr>
<tr><td class="Line" height=1 colspan=2><img src="images/spacer.gif" width=1 height=1 border=0 alt=""></td></tr>
<tr><td colspan=2>&nbsp;</td></tr>

<tr FOREACH="product.productOptions,option">
    <td IF="option.opttype=#Text#"  width="30%" height=25 valign=middle class="ProductDetails">{option.opttext:h}:&nbsp;</td>
    <td IF="option.opttype=#Textarea#"  width="30%" height=25 valign=top class="ProductDetails">{option.opttext:h}:&nbsp;</td>
    <td IF="option.opttype=#SelectBox#"  width="30%" height=25 valign=middle class="ProductDetails">{option.opttext:h}:&nbsp;</td>
    <td IF="option.opttype=#Radio button#"  width="30%" height=25 valign=top class="ProductDetails">{option.opttext:h}:&nbsp;</td>
    <td IF="!option.empty">

            <!-- option select -->
            <select IF="option.opttype=#SelectBox#" name="product_options[{option.optclass}]">
                <option FOREACH="option.productOptions,opt" value="{opt.option_id}">{opt.option:h}
                <widget template="modules/ProductOptions/product_option_modifier.tpl" opt="{opt}" option="{option}" hidePrice="{!product.displayPriceModifier}">
				</option>
            </select>

            <!-- option radio button -->
            <table IF="option.opttype=#Radio button#" border=0 cellpadding=0 cellspacing=0>
            <tr FOREACH="option.productOptions,oid,opt">
            <td align=left>
                <input type=radio name="product_options[{option.optclass}]" value="{opt.option_id}" checked="{!oid}">
            </td>
            <td>{opt.option:h}
			<widget template="modules/ProductOptions/product_option_modifier.tpl" opt="{opt}" option="{option}" hidePrice="{!product.displayPriceModifier}">
			</td>
            </tr>
            </table>

    </td>        
    <td IF="option.empty">
        <!-- text input -->
        <input type="text" IF="option.opttype=#Text#" name="product_options[{option.optclass}]" value="" size="{option.cols}"/>

        <!-- textarea input -->
        <textarea IF="option.opttype=#Textarea#" cols="{option.cols}" rows="{option.rows}" name="product_options[{option.optclass}]"></textarea>
    </td>
</tr>

<widget template="modules/ProductOptions/options_exception.tpl">

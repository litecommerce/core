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
<table border="0" cellspacing="0" cellpadding="0">
<tr id="optionsTitle"><td class="ProductDetailsTitle" colspan=2>Options:</td></tr>
<tr FOREACH="item.product.productOptions,option">
    <td IF="option.opttype=#Text#"  width="30%" height=25 valign=middle class="ProductDetails">{option.opttext:h}:&nbsp;</td>
    <td IF="option.opttype=#Textarea#"  width="30%" height=25 valign=top class="ProductDetails">{option.opttext:h}:&nbsp;</td>
    <td IF="option.opttype=#SelectBox#"  width="30%" height=25 valign=middle class="ProductDetails">{option.opttext:h}:&nbsp;</td>
    <td IF="option.opttype=#Radio button#"  width="30%" height=25 valign=top class="ProductDetails">{option.opttext:h}:&nbsp;</td>
    <td IF="!option.empty">

            <!-- option select -->
            <select class="FixedSelect" IF="option.opttype=#SelectBox#" name="clone_products[{item.uniqueKey:h}][product_options][{option.optclass}]">
                <option FOREACH="option.productOptions,opt" value="{opt.option_id}" selected="{optionSelected(item,opt)}">{opt.option:h}
                <widget template="modules/AOM/product_option_modifier.tpl" opt="{opt}" option="{option}">
				</option>
            </select>

            <!-- option radio button -->
            <table IF="option.opttype=#Radio button#" border=0 cellpadding=0 cellspacing=0>
            <tr FOREACH="option.productOptions,oid,opt">
            <td align=left>
                <input type=radio name="clone_products[{item.uniqueKey:h}][product_options][{option.optclass}]" value="{opt.option_id}" {if:optionSelected(item,opt)}checked{end:}>
            </td>
            <td>{opt.option:h}
			<widget template="modules/AOM/product_option_modifier.tpl" opt="{opt}" option="{option}">
			</td>
            </tr>
            </table>

            <!-- text input -->
            <span IF="option.opttype=#Text#">{option.options:h}</span>

            <!-- textarea input -->
            <span IF="option.opttype=#Textarea#">{option.options:h}</span>
    </td>        
    <td IF="option.empty">
        <!-- text input -->
        <input type="text" IF="option.opttype=#Text#" name="clone_products[{item.uniqueKey:h}][product_options][{option.optclass}]" value="{item.getProductOptionValue(option.optclass):h}" size="{option.cols}"/>

        <!-- textarea input -->
        <textarea IF="option.opttype=#Textarea#" cols="{option.cols}" rows="{option.rows}" name="clone_products[{item.uniqueKey:h}][product_options][{option.optclass}]">{item.getProductOptionValue(option.optclass):r}</textarea>
    </td>
</tr>
</table>

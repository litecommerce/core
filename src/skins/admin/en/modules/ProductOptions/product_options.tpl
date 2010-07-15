{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product options management template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<span IF="product.hasOptions()&mm.activeModules.WholesaleTrading">

  <form action="admin.php" method="POST" name="expansion_form">

    <input FOREACH="allparams,name,val" type="hidden" name="{name}" value="{val}" />
    <input type="hidden" name="action" value="update_limit" />

    <table>

      <tr>
        <td>
          Display product options as a list of variants on the product details page: <br />
          (requires WholesaleTrading module functionality enabled)
        </td>
        <td><input type="checkbox" name="expansion_limit" checked="{product.expansion_limit}" /></td>
      </tr>

      <tr>
        <td colspan="2"><input type="submit" value=" Update "></td>
      </tr>

    </table>

  </form>

</span>

<span IF="product.hasOptions()">

  <widget module="ProductOptions" template="modules/ProductOptions/option_form_js.tpl" />

  <table border=0 cellpadding=1 cellspacing=3>

    <!-- product options list -->
    <tr>
      <td colspan=4 class=AdminHead>Product options</td>
    </tr>

  </table>

  <br />

  <form FOREACH="product.productOptions,idx,option" action="admin.php" method="POST" name="product_option_{option.option_id}">

    <input FOREACH="allparams,name,val" type="hidden" name="{name}" value="{val}" />
    <input type="hidden" name="action" value="update_product_option" />
    <input type="hidden" name="option_id" value="{option.option_id}" />


    <table border=0 cellpadding=1 cellspacing=3>

      <tbody>

        <tr>
          <td class=TableHead colspan=3>

            <table border=0 cellpadding=0>

              <tr>
                <td><a name="section_{option.option_id}"></a>Option class name:&nbsp;</td>
                <td IF="!option.parent_option_id">
                  <input type=text name="optdata[optclass]" value="{option.optclass:r}" size="12" />
                  <widget class="\XLite\Module\ProductOptions\Validator\RequiredValidator" field="optdata[optclass]" action="update_product_option" option_id="{option.option_id}">
                  &nbsp;
                  <span style="color: #606060">should be unique for easier stock management</span>
                </td>
                <td IF="option.parent_option_id">
                  <b>{option.optclass}</b><input type=hidden name="optdata[optclass]" value="{option.optclass:r}">
			            &nbsp;
                  <span style="color: #606060">[GLOBAL]</span>
                </td>
              </tr>

            </table>

          </td>
        </tr>

        <tr>
          <td class="TableRow">Option values</td>
          <td class="TableRow">Option selection text</td>
          <td class="TableRow">Pos.</td>
        </tr>

        <tr>
          <td rowspan=3>
          	<textarea cols=34 rows=5 name="optdata[options]">{option.options:r}</textarea>
          </td>
          <td valign=top>
            <input type=text name="optdata[opttext]" value="{option.opttext:r}" size=34>
          </td>
          <td valign=top>
        		<input type=text name="optdata[orderby]" value="{option.orderby}" size=3>
          </td>
        </tr>

        <tr>
          <td class="TableRow">

            <table border=0 cellpadding=0>

              <tr>
                <td>Option selector</td>
              	<td class="TableRow" id="TextTRHead_{idx}" style="display: none">&nbsp;&nbsp;&nbsp;Size (symbols)</td>
              	<td class="TableRow" id="TextareaTRHead_{idx}" style="display: none">&nbsp;&nbsp;&nbsp;Size (cols, rows)</td>
              </tr>

            </table>

          </td>
          <td>&nbsp;</td>
        </tr>

        <tr>
          <td>

            <table border=0 cellpadding=0>
              <tr>
                <td>
                  <select name="optdata[opttype]" id="opttype_{idx}" onChange="javascript: editSize(this.value, '_{idx}');">
                    <option value="Text" selected="option.opttype=#Text#">Text</option>
                    <option value="Textarea" selected="option.opttype=#Textarea#">Text Area</option>
                    <option value="SelectBox" selected="option.opttype=#SelectBox#">Select Box</option>
                    <option value="Radio button" selected="option.opttype=#Radio button#">Radio Button</option>
                  </select>
                </td>
               	<td id="TextTR_{idx}" style="display: none">
              		<input type=text name="optdata[cols]" size=3 value="{option.cols}">
              	</td>
                <td id="TextareaTR_{idx}" style="display: none">
                  <input type=text name="optdata[rows]" size=3 value="{option.rows}">
                </td>
          		</tr>

        		</table>

            <script language="Javascript">initEditSize("_{idx}");</script>

          </td>
          <td>&nbsp;</td>
        </tr>

        <tr>
          <td colspan=2>
            <input type="button" name="update" value="Update" onclick="document.product_option_{option.option_id}.action.value='update_product_option'; document.product_option_{option.option_id}.submit();" />
        	</td>
        	<td align=right IF="option.parent_option_id">
        		<a href="admin.php?target=global_product_options"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle">Delete Global product option</a>
        	</td>
        	<td align=right IF="!option.parent_option_id">
        		<input type="button" name="delete" value="Delete" onClick="document.product_option_{option.option_id}.action.value='delete_product_option'; document.product_option_{option.option_id}.submit();" />
          </td>
        </tr>

        <tr>
          <td colspan=3><hr /></td>
        </tr>

      </tbody>

    </table>

  </form>

</span>

<span IF="!product.hasOptions()">

  <table border=0 cellpadding=1 cellspacing=5>

    <tr>
    	<td class=AdminHead>Product options</td> 
    </tr>

    <tr>
      <td>No product options defined.</td> 
    </tr>

  </table>

<hr />

</span>

<!-- add product option form -->

<form action="admin.php" method="POST" name="add_option_form">

  <input FOREACH="allparams,name,val" type="hidden" name="{name}" value="{val}" />
  <input type="hidden" name="action" value="add_product_option" />
  <input type=hidden name="opttype" value="" />

  <table border=0 cellpadding=1 cellspacing=5>

    <tbody>

      <tr>
        <td colspan=3><font class=AdminTitle>Add product option class</font></td>
      </tr>

      <widget module="ProductOptions" template="modules/ProductOptions/option_form.tpl" action="add_product_option">

      <tr>
        <td colspan=3><br><input type="submit" name="add" value=" Add "></td>
      </tr>

    </tbody>

  </table>

</form>

<table border=0 cellpadding=1 cellspacing=5>

<tbody IF="product.hasOptions()">

<tbody IF="!product.productOptionsNumber=#1#">
<tr>
    <td colspan="4">&nbsp;</td>
</tr>

<tbody IF="product.hasExceptions()">
<!-- product option exceptions -->
<tr> <td colspan=4 class=AdminHead>Excluded option combinations</td> </tr>
<tbody FOREACH="product.optionExceptions,k,exception">
<form action="admin.php" method="get" name="change_exception_{exception.option_id}">
<input FOREACH="allparams,name,val" type="hidden" name="{name}" value="{val}"/>
<!--input type="hidden" name="target" value="product_exception"-->
<input type="hidden" name="action" value="update_option_exception">
<!--input type="hidden" name="product_id" value="{product.product_id}"-->
<input type="hidden" name="option_id" value="{exception.option_id}">
<tr class="{getRowClass(k,#TableRow#,#TableHead#)}">
    <td colspan=4>
		<table border=0 cellpadding=0 width=100%>
		<tr>
			<td width=100%>{exception.exception:r}</td>
			<td>
        	<input type="button" name="delete" value="Delete" onclick="document.change_exception_{exception.option_id}.action.value='delete_option_exception'; document.change_exception_{exception.option_id}.submit();">
			</td>
		</tr>
		</table>
    </td>
</tr>
</form>
</tbody>

<tr>
    <td colspan=4>&nbsp;</td>
</tr>

</tbody>

<tbody>
<!-- add exception form -->
<form action="admin.php" method="POST" name="exception_form">
<input FOREACH="allparams,name,val" type="hidden" name="{name}" value="{val}"/>
<input type="hidden" name="action" value="add_option_exception">
<input type="hidden" name="exception" id="option_exlude">

<tr> <td colspan=4><font class=AdminTitle>Exclude option combination</font></td> </tr>

<tr> 
	<td colspan=4>
		<table border=0 cellpadding=0>
		<tr>
            <td FOREACH="product.productOptions,idx,option">
        		<table border=0 cellpadding=0>
        		<tr>
            		<td nowrap><b>{option.optclass}:</b>&nbsp;</td>
            		<td>
                        <select name="optdata[opttype]" id="option_exlude_select_{idx}">
                            <option FOREACH="option.productOptions,opt" value="{opt.option}">{opt.option}</option>
                            <option value="">- NONE -</option>
                        </select>
            		</td>
        		</tr>
        		</table>
            <script language="Javascript">
            OptionClassesArray[OptionClassesArray.length] = "{option.optclass}";
            OptionClassesExludeSelectsArray[OptionClassesExludeSelectsArray.length] = "option_exlude_select_{idx}";
            </script>
            </td>
		</tr>
		</table>
	</td> 
</tr>

<tr> <td colspan=4><input type=button value=" Add " onClick="AddExcludeCombination()"></td> </tr>
</form>
</tbody>

</tbody>

<!-- add options validator -->
<tbody>

<form action="admin.php" method="POST" name="option_validator_form">
<input FOREACH="allparams,name,val" type="hidden" name="{name}" value="{val}"/>
<input type="hidden" name="action" value="product_option_validator">

<tr> <td colspan=4><font class=AdminTitle><br>JavaScript enhancements</font></td> </tr>
<tr> <td valign=top colspan=4><textarea cols=75 rows=10 name="javascript_code">{product.optionValidator:r}</textarea></td></tr>
<tr>
    <td colspan="4">
        <input IF="!product.optionValidator" type="submit" value=" Add "/>
        <input IF="product.optionValidator" type="submit" value=" Update "/>
        <input IF="product.optionValidator" type="button" value=" Delete " onClick="javascript: document.option_validator_form.javascript_code.value=''; document.option_validator_form.submit()"/>
    </td>
</tr>

<tr id="PO_JSExample_URL" style="display:">
    <td colspan="4">
    <a href="javascript:ShowPOJSExamples();" class="NavigationPath" onClick="this.blur()"><b>Example &gt;&gt;&gt;</b></a>
    </td>
</tr>
<tr id="PO_JSExample" style="display: none">
    <td colspan="4">
    <b>JavaScript code example:</b> (consult ProductOptions module reference manual for additional details)<br>
    <table border="1">
    <tr>
        <td width=550>
<code>
if (product_option('Name').selectedIndex == 0) { <br>
   alert("You have chosen Simba! Simba is a lion cub who can't wait to be King.\nHis eyes are burgundy on yellow."); <br>
} <br>
if (product_option('Name').selectedIndex == 1) { <br>
   alert("You have chosen Nala! Nala is Simba's best friend.\nHer eyes are aquamarine on yellow."); <br>
} <br>
if (product_option('Name').selectedIndex == 2 || product_option('Name').selectedIndex == 3) { <br>
   selection = confirm("Kiara and Kovu are special order items and will take 2 to 4 weeks to deliver. Do you want to add a cub to your cart?"); <br>
   return selection; <br>
} <br>
return true; <br>
</code>
        </td>
    </tr>
    </table>
</tr>
</tbody>

<form>
</tbody>

</table>

<script language="Javascript" IF="option_id">
document.location = "#section_" + {option_id}; 
</script>

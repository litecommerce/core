{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category extra fields template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

Use this section to define additional product detail fields for the entire category (e.g. 'Author' or 'ISBN' for category 'Books').<hr>

<br />

<p align=justify><b>Note: </b>It is strongly recommended that you do not use duplicate names for category extra fields.</p> 

<form IF="extraFields" action="admin.php" method=POST>

  <input type="hidden" foreach="allparams,key,v" name="{key}" value="{v}" />
  <input type=hidden name=action value=update_fields />

  <table border=0 cellpadding=0 cellspacing=0>

    <tr>
      <td colspan=4 class=AdminHead>Available category extra fields</td>
    </tr>

    <tr>
      <td colspan=4>&nbsp;</td>
    </tr>

    <tr>
      <td class="CenterBorder">

        <table border="0" cellspacing="1" cellpadding="2">
          <tr class=TableHead>
    		    <th class="TableHead">Pos.</th>
		        <th class="TableHead">Field name</th>
		        <th class="TableHead">Field default value</th>
    		    <th class="TableHead">Enabled</th>
    		    <th class="TableHead">Delete</th>
          </tr>

      		<tr FOREACH="extraFields,idx,ef" class="{getRowClass(idx,#DialogBox#,#TableRow#)}">
		        <td><input type=text name="extra_fields[{ef.field_id}][order_by]" value="{ef.order_by}" size=4 /></td>
    		    <td><input type=text name="extra_fields[{ef.field_id}][name]" value="{ef.name:r}" size=32 maxlength=255 /></td>
    		    <td><input type=text name="extra_fields[{ef.field_id}][default_value]" value="{ef.default_value:r}" size=32 maxlength=255 /></td>
    		    <td>
		          <select name="extra_fields[{ef.field_id}][enabled]">
		            <option value=1 selected="ef.enabled">Yes</option>
		            <option value=0 selected="!ef.enabled">No</option>
		          </select>
		        </td>
    		    <td align=center>
		          <input type=checkbox name="delete_fields[]" value="{ef.field_id}" />
    		    </td>
          </tr>

    		</table>

    	</td>
    </tr>

    <tr>
      <td colspan=4>&nbsp;</td>
    </tr>

    <tr>
      <td colspan=4><input type=submit name=update value=Update class="DialogMainButton" />&nbsp;&nbsp;<input type=submit name=delete value="Delete selected" /></td>
    </tr>

    <tr>
      <td colspan=4>&nbsp;</td>
    </tr>

  </table>

</form>


<form action="admin.php" method=POST>

  <input type="hidden" foreach="allparams,key,v" name="{key}" value="{v}" />
  <input type=hidden name=action value=add_field />
  <input type=hidden name="add_categories[0]" value={category_id} />

  <table border=0 cellpadding=3 cellspacing=1>

    <tr>
      <td colspan=3 class=AdminTitle>Add category extra field</td>
    </tr>    

    <tr>
      <td colspan=3>&nbsp;</td>
    </tr>

    <tr>
      <td>Field name:</td>
      <td class=Star width=10>*</td>
      <td>
        <input type=text name=add_ef_name value="{add_ef_name:r}" size=32 maxlength=255 />
        <widget class="\XLite\Validator\RequiredValidator" field="add_ef_name" />
      </td>
    </tr>

    <tr>
      <td>Default value:</td>
      <td>&nbsp;</td>
      <td><input type=text name=add_ef_default_value value="{add_ef_default_value:r}" size=32 maxlength=255 /></td>
    </tr>

    <tr>
      <td>Pos.:</td>
      <td>&nbsp;</td>
      <td><input type=text name=add_ef_order_by value="{add_ef_order_by}" size=4 maxlength=4 /></td>
    </tr>

    <tr>
      <td>Enabled:</td>
      <td>&nbsp;</td>
      <td>
        <select name=add_ef_enabled>
          <option value=1 selected="add_ef_enabled=#1#">Yes</option>
          <option value=0 selected="add_ef_enabled=#0#">No</option>
        </select>
      </td>
    </tr>

    <tr>
      <td colspan=3>
        <input type=submit name=add_field value=" Add ">
        {if:target=#extra_fields#}
        &nbsp;&nbsp;<input type=submit name=delete_field value=" Delete " />
        {end:}
      </td>
    </tr>

  </table>

</form>


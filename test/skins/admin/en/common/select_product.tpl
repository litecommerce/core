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
<span id="{name}_label">{product.name}</span>&nbsp;
<input type="hidden" name="{formField}_id" value="{product.product_id}"><input type="button" value="{label}" onclick="window.open('admin.php?target=product_popup&formName={formName}&spanName={name}_label&formField={formField}','selectproduct','width=600,height=550,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no')">&nbsp;&nbsp;&nbsp;<input type="button" onclick="document.getElementById('{name}_label').innerHTML='';document.{formName}.{formField}_id.value=''" IF="removeButton&product.product_id" value="Clear"/>

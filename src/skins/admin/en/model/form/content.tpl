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

{* TODO (FlexyCompiler) - improve the approach to access array fields *}
{* TODO (FlexyCompiler) - add the ability to use constants *}

<div FOREACH="getFormFields(),section,data" class="{section}-section">
  <table class="{section}-table">
    <tr>{data.sectionParamWidget.display()}</tr>
    <tr FOREACH="data.sectionParamFields,field">{field.display()}</tr>
  </table>
</div>


{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Settings widget
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
{if:option.isName(#prohibit_pay_gc#)}
  <select name="{option.name}">
    <option value="N" selected="{option.value=#N#}">Never</option>
    <option value="O" selected="{option.value=#O#}">If cart contains only gift certificates</option>
    <option value="P" selected="{option.value=#P#}">If cart contains gift certificates as well as products</option>
  </select>
{end:}

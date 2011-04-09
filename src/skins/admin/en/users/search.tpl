{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Users management main template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<widget template="common/dialog.tpl" body="users/search_form.tpl" />

<span IF="mode=#search#|mode=#list#">
  <br />
  {getUsersCount()} account(s) found
  <br />
  <widget template="common/dialog.tpl" head="Search results" body="users/search_results.tpl" IF="{getUsersCount()}" />
</span>

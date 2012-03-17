{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Search form and list wrapper
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<div class="top-controls">
  <div class="form-panel users-search-panel">

    <form name="searchform" method="get" action="admin.php">
      <input FOREACH="getURLParams(),name,value" type="hidden" name="{name}" value="{value}" />

      <table cellpadding="1" cellspacing="5">
        <list name="search_form" type="inherited" />
      </table>

    </form>

  </div>
</div>

<div class="clear"></div>

<widget template="{getBody()}" />

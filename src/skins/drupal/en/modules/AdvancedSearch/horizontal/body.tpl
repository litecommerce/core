{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Advanced search
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget class="XLite_Module_AdvancedSearch_View_Form_Search" name="adsearch_form" className="advanced-search" />

  <table cellspacing="0" class="form-table">

    {displayViewListContent(#advsearch.horizontal.childs#)}

  </table>

<widget name="adsearch_form" end />

<script type="text/javascript">
<!--
new advancedSearchController($('.advanced-search'));
-->
</script>

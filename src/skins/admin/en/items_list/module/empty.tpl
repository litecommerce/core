{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<div class="main-text">

  <div class="no-modules-found" IF="getSearchSubstring()">
    {t(#No modules found for search_string#,_ARRAY_(#search_string#^getSearchSubstring())):h}
  </div>

  <div class="no-modules-found" IF="!getSearchSubstring()">
    {t(#No modules found#)}
  </div>

  <div class="clarify-text" IF="getSearchSubstring()">
    {t(#Please, clarify your search request#)}
  </div>

</div>

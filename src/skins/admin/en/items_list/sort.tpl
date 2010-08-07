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

<a IF="isSortByModeSelected(sortBy)" class="sort-order selected" href="{getActionUrl(_ARRAY_(#sortOrder#^getSortOrderToChange()))}">{title:h}&nbsp;{if:isSortOrderAsc()}&darr;{else:}&uarr{end:}</a>
<a IF="!isSortByModeSelected(sortBy)" class="sort-order" href="{getActionUrl(_ARRAY_(#sortBy#^sortBy))}">{title:h}</a>

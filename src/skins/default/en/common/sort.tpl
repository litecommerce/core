{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Sort widget
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<widget class="\XLite\View\Form\Sort" name="sort_form" params="{getFormParams()}" />

<span>{t(#Sort by#)}</span>
<select name="sortCriterion">
  <option FOREACH="getParam(#sortCriterions#),key,name" value="{key}" selected="{isSortCriterionSelected(key)}">{name}</option>
</select>
<a href="{getSortOrderURL()}" class="{getSortOrderLinkClassName()}">{if:isSortOrderAsc()}&darr;{else:}&uarr;{end:}</a>

<widget name="sort_form" end />
<script type="text/javascript">
$(document).ready(
  function() {
    $('.sort-box').each(
      function() {
        new SortBoxController(this);
      }
    );
  }
);
</script>

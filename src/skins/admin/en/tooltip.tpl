{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Tooltip widget
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<span class="tooltip-main">
  {displayCommentedData(getHelpText())}
  <img IF="getParam(#isImage#)" class="tooltip-caption {getParam(#className#)}" src="images/spacer.gif" alt="Help" />
  <span IF="!getParam(#isImage#)" class="tooltip-caption {getParam(#className#)}">{getParam(#caption#)}</span>
</span>
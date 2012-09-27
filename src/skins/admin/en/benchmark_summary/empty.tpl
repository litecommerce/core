{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Benchmark summary - empty variant
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<p>{t(#Run benchmark to assess your server performance#)}</p>
<div class="buttons">
  <widget class="\XLite\View\Button\Link" location="{buildURL(#measure#,#measure#)}" label="{t(#Run benchmark#)}" style="action" />
  <widget
    class="\XLite\View\Tooltip"
    id="measure-help-text"
    text="{t(#The benchmark evaluates server environment#):h}"
    caption="{t(#What is benchmark?#)}"
    isImageTag="false"
    className="help" />
</div>
<span class="help-text" style="display: none;">{t(#The benchmark evaluates server environment#):h}</span>

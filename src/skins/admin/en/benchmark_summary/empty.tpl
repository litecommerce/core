{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Benchmark summary - empty variant
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<p>{t(#Please run the benchmark test in order to estimate your server performance#)}</p>
<div class="buttons">
  <widget class="\XLite\View\Button\Link" location="{buildURL(#measure#,#measure#)}" label="{t(#Run benchmark#)}" style="action" />
  <a id="measure-help-text" href="{buildURL(#measure#,#help#)}" class="help">{t(#What is benchmark?#)}</a>
</div>
<span class="help-text" style="display: none;">{t(#The benchmark evaluates server environment#):h}</span>

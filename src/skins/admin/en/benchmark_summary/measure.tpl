{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Measure info
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<p class="completed">{t(#Benchmark completed in #)} <span class="score{if:isHighScore()} score-high{end:}">{measure.total}</span> {t(#ms#)}</p>
<p class="date">{formatDate(lastDate)}</p>
<div class="buttons buttons-rerun">
  <widget class="\XLite\View\Button\Link" location="{buildURL(#measure#,#measure#)}" label="{t(#Rerun benchmark#)}" />
  <a id="measure-help-text" href="{buildURL(#measure#,#help#)}" class="help">{t(#What is benchmark?#)}</a>
</div>
{if:getHostingScore()}
<hr />
<p class="compare">{t(#Compare your result with other servers#)}:<span class="mark">*</span></p>
<table cellspacing="0" >
  <tr FOREACH="getHostingScore(),score" title="{score.name}">
    <td class="name">
      {if:score.link}
        <a href="{score.link}">{score.name}</a>
      {else:}
        {score.name}
      {end:}
    </td>
    <td class="dash">&mdash;</td>
    <td><span class="score">{score.score}</span> {t(#ms#)}</td>
  </tr>
</table>

<span class="note"><span class="mark">*</span> {t(#The values are average#)}</span>
{end:}
<span class="help-text" style="display: none;">{t(#The benchmark evaluates server environment#):h}</span>

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Steps block
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="steps step-{getStepNumber()}">

  <div FOREACH="getSteps(),stepKey,step" class="step {stepKey}-step {if:isCurrentStep(step)}current{else:}inactive{end:} {if:isPreviousStep(step)}previous{end:} {if:hasLeftArrow(step)}left-arrow{end:} {if:hasRightArrow(step)}right-arrow{end:}">
    <h2><img src="images/spacer.gif" class="arrow left" alt="" IF="hasLeftArrow(step)" /><span class="text">{step.getTitle()}</span><img src="images/spacer.gif" class="arrow right" alt="" IF="hasRightArrow(step)" /></h2>
    <div class="step-box">{step.display()}</div>
  </div>

  <div class="clear"></div>

</div>

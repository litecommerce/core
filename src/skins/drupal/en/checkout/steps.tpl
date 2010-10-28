{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Steps block
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="steps step-{getStepNumber()}">

  <div FOREACH="getSteps(),stepKey,step" class="step {stepKey}-step {if:isCurrentStep(step)}current{else:}inactive{end:} {if:isPreviousStep(step)}previous{end:}">
    <h2>{step.getTitle()}</h2>
    <div class="step-box">{step.display()}</div>
  </div>

  <div class="clear"></div>

</div>

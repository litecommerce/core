{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Option exception note
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div id="optionsException" IF="invalid_options">
  The option combination you selected:

  <ul>
    <li FOREACH="invalid_options,option,value"><strong>{option:h}:</strong> {value:h}</li>
  </ul>

  is not available. Please make another choice.
</div>

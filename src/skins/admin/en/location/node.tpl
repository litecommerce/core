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

<div class="location-node{if:subnodes} expandable{end:}">

  {if:getParam(%static::PARAM_LINK%)}<a href="{getParam(%static::PARAM_LINK%)}" class="location-title">{end:}{getParam(%static::PARAM_NAME%):h}{if:getParam(%static::PARAM_LINK%)}</a>{end:}

  <ul class="location-subnodes" IF="subnodes">
    <li FOREACH="subnodes,node"><a href="{node.getParam(%static::PARAM_LINK%)}">{node.getParam(%static::PARAM_NAME%)}</a></li>
  </ul>

</div>

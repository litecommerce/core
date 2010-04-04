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

<widget template="{getDir()}/header.tpl" />

  <widget class="{getFormClass()}" name="{getFormName()}" IF="getFormParams()" formParams="{getFormParams()}" />
  <widget class="{getFormClass()}" name="{getFormName()}" IF="!getFormParams()" />

    <widget template="{getDir()}/{getFormDir()}/header.tpl" />

    {foreach:getFormFields(),name,field}{field.display()}{end:}
  
    <widget template="{getDir()}/{getFormDir()}/buttons.tpl" />

    <widget template="{getDir()}/{getFormDir()}/footer.tpl" />

  <widget name="{getFormName()}" end />

<widget template="{getDir()}/footer.tpl" />


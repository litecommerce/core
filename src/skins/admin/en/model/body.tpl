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

<script IF="getTopInlineJSCode()" type="text/javascript">{getTopInlineJSCode():r}</script>

<widget template="{getDir()}/header.tpl" />

  <widget class="{getFormClass()}" name="{getFormName()}" />

    <widget template="{getDir()}/form_content.tpl" />
    <br /><widget template="{getDir()}/{getFormTemplate(#buttons#)}" />

  <widget name="{getFormName()}" end />

<widget template="{getDir()}/footer.tpl" />

<script IF="getBottomInlineJSCode()" type="text/javascript">{getBottomInlineJSCode():r}</script>


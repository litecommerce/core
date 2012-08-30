{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Suppliers list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<div class="{getListCSSClasses()}">

  <ul class="list" IF="getPageData()">
    {foreach:getPageData(),method}
      <li class="{getLineClass(method)}">

        <div IF="method.getAdminIconURL()" class="icon"><img src="{method.getAdminIconURL()}" alt="" /></div>

        <div class="row">

        <div class="action left-action">
          {if:canSwitch(method)}
            {if:method.getWarningNote()}

              {if:method.getEnabled()}
                <div class="switch enabled"><img src="images/spacer.gif" alt="{t(#Enabled#)}" /></div>
              {else:}
                <div class="switch disabled"><img src="images/spacer.gif" alt="{t(#Disabled#)}" /></div>
              {end:}

            {else:}              

              {if:method.getEnabled()}
                <div class="switch enabled"><a href="{buildURL(#payment_settings#,#disable#,_ARRAY_(#id#^method.getMethodId()))}"><img src="images/spacer.gif" alt="{t(#Disable#)}" /></a></div>
              {else:}
                <div class="switch disabled"><a href="{buildURL(#payment_settings#,#enable#,_ARRAY_(#id#^method.getMethodId()))}"><img src="images/spacer.gif" alt="{t(#Enable#)}" /></a></div>
              {end:}

            {end:}
          {else:}
            {if:canEnable(method)}
              <div class="switch enabled"><img src="images/spacer.gif" alt="{method.getForcedEnabledNote()}" /></div>
            {else:}
              <div class="switch disabled"><img src="images/spacer.gif" alt="{method.getForbidEnableNote()}" /></div>
            {end:}
          {end:}
          <img src="images/spacer.gif" class="separator" alt="" />
        </div>

        <div class="title">{method.getName()}</div>

        <div IF="hasRightActions(method)" class="action right-action">
          <img src="images/spacer.gif" class="separator" alt="" />
          <div IF="canRemoveMethod(method)" class="remove"><a href="{buildURL(#payment_settings#,#remove#,_ARRAY_(#id#^method.getMethodId()))}" title="{t(#Remove#)}"><img src="images/spacer.gif" alt="" /></a></div> 
          {if:method.getWarningNote()}
            <img IF="canRemoveMethod(method)" src="images/spacer.gif" class="subseparator" alt="" />
            <div class="warning"><a href="{method.getConfigurationURL()}"><img src="images/spacer.gif" alt="{method.getWarningNote()}" /></a></div>
          {elseif:method.isTestMode()}
            <img IF="canRemoveMethod(method)" src="images/spacer.gif" class="subseparator" alt="" />
            <div class="test-mode"><a href="{method.getConfigurationURL()}" title="{t(#Test mode#)}"><img src="images/spacer.gif" alt="" /></a></div>
          {elseif:method.isConfigurable()}
            <img IF="canRemoveMethod(method)" src="images/spacer.gif" class="subseparator" alt="" />
            <div class="configure"><a href="{method.getConfigurationURL()}" title="{t(#Configure#)}"><img src="images/spacer.gif" alt="" /></a></div>
          {end:}
        </div>

        </div>

        <widget IF="isSeparateConfigureButtonVisible(method)" class="XLite\View\Button\Link" label="{t(#Configure#)}" location="{method.getConfigurationURL()}" style="configure"/>

      </li>

    {end:}

  </ul>

</div>

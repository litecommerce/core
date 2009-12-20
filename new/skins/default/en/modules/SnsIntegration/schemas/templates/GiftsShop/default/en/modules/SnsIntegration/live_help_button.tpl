<!-- [Sales-n-Stats live help button] -->
<table IF="config.SnsIntegration.showOperatorButton" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td IF="!xlite.config.GiftsShop.useLocalSnsImages" align="center">
        <img style="display: none; cursor: pointer" onclick="javascript:window.open('{snsTrackerURL}/openChat.{config.SnsIntegration.collectorLanguage}', '_blank', 'status=yes,toolbar=no,menubar=no,location=no,width=500,height=400')" border="0" src="{snsTrackerURL}/operatorButton.js.{config.SnsIntegration.collectorLanguage}" alt="Powered by Sales-n-Stats" id="snsOperatorButton">
        <script type="text/javascript">document.getElementById('snsOperatorButton').style.display = '';</script>
        <noscript>
            <a href="{snsTrackerURL}/leaveMessage.{config.SnsIntegration.collectorLanguage}?noscript=true" target="_blank">
                <img border="0" src="{snsTrackerURL}/operatorButton.js.{config.SnsIntegration.collectorLanguage}?script=no" style="cursor: pointer" alt="Powered by Sales-n-Stats">
            </a>
        </noscript>
    </td>
    <td IF="xlite.config.GiftsShop.useLocalSnsImages" align="center">
        <script type="text/javascript" language="JavaScript">
        <!--
        function writeSnsImage(layoutPath, trackerURL, lang) // {{{
            {
                var imgsrc = '';
                if (typeof(trackerIsLiveHelpAvailable) != 'undefined' && trackerIsLiveHelpAvailable) {
                    imgsrc = layoutPath + "images/custom/modules/SnsIntegration/live_help.jpg";
                } else {
                    imgsrc = layoutPath + "images/custom/modules/SnsIntegration/leave_message.jpg";
                }

                document.write("<img style=\"cursor: pointer\" onclick=\"javascript:window.open('" + trackerURL + "/openChat." + lang + "', '_blank', 'status=yes,toolbar=no,menubar=no,location=no,width=500,height=400')\" border=\"0\" src=\"" + imgsrc + "\" alt=\"Powered by Sales-n-Stats\" id=\"snsOperatorButton\">");
            } // }}}
        -->
        </script>
        <script type="text/javascript" language="JavaScript">writeSnsImage("{xlite.layout.path}", "{snsTrackerURL}", "{config.SnsIntegration.collectorLanguage}");</script>
        <noscript>
            <a href="{snsTrackerURL}/leaveMessage.{config.SnsIntegration.collectorLanguage}?noscript=true" target="_blank">
                <img border="0" src="{xlite.layout.path}images/custom/modules/SnsIntegration/leave_message.jpg" style="cursor: pointer" alt="Powered by Sales-n-Stats">
            </a>
        </noscript>
    </td>
</tr>
<tr IF="!xlite.config.GiftsShop.useLocalSnsImages">
    <td height="15" align="center" bgcolor="#426195">
        <font size="1" face="Arial">
            <a href="http://www.sales-n-stats.com" style="text-decoration: none; color: #ffffff" target="_blank"><b>Powered by Sales-n-Stats</b></a>
        </font>
    </td>
</tr>
</table>
<!-- [/Sales-n-Stats live help button] -->

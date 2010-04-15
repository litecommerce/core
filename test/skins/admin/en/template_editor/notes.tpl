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
<script type="text/javascript">
function visibleBox(id, status)
{
    var Element = document.getElementById(id);
    if (Element) {
        Element.style.display = ((status) ? "" : "none");
    }
}
function ShowNotes()
{
    visibleBox("notes_url", false);
    visibleBox("notes_body", true);
}
</script>

<br />
<span id="notes_url" style="display:"><a href="javascript: ShowNotes();" class="NavigationPath" onClick="this.blur()"><b> Please note  &gt;&gt;&gt;</b></a></span>
<span id="notes_body" style="display: none">
<hr noshade size="1">
<p>Since it is possible to modify the existing Flexy (<i>Flexy is a templates engine used in Litecommerce</i>) structures or add a custom Flexy code to the templates or user-defined pages, the following rules should be observed:</p>

<p><i>- For braces ("&#123;" or "&#125;") to be displayed only for the text decoration the HTML code should be used:</i></p>
<p><b>&amp;#123;</b> - for the "&#123;" symbol</p>
<p><b>&amp;#125;</b> - for the "&#125;" symbol</p>
<p>Symbols "&#123;" and "&#125;" themselves are Flexy code structures. These symbols should be used only in the Flexy code.</p>
<p><i>- For a JavaScript code to be added to a template or a user-defined page, symbols "&#123;" and "&#125;" should be placed in a new line. For example :</i></p>
<br />
&lt;script type="text/javascript"&gt;<br />
&#123;<br />
&nbsp;&nbsp;my_function();<br />
&#125;<br />
&lt;/script&gt;
<br />
<br />
instead of 
<br />
<br />
&lt;script type="text/javascript"&gt;&#123;my_function();&#125;&lt;/script&gt;
<br />
<p>This code record is <b>incorrect</b> and will be interpreted by Flexy as a control structure but not as a JS code.</p>

<p>If you have any technical questions or difficulties with template customization, please feel free to contact our Support team.</p>
<hr noshade size="1">
</span>

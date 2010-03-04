{* SVN $Id$ *}
<a href="{href:r}" target="{hrefTarget:r}">
  {if:img}
    <img src="images/{img}" align="absmiddle" />
  {else:}
    <img src="images/go.gif" width="13" height="13" align="absmiddle" />
  {end:}
  {if:font}
    <font class="{font}">
  {end:}
  {label:h}
  {if:font}
    </font>
  {end:}
</a>

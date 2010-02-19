{* SVN $Id$ *}
<a href="{widget.href:r}" target="{widget.hrefTarget:r}">
  {if:widget.img}
    <img src="images/{widget.img}" align="absmiddle" />
  {else:}
    <img src="images/go.gif" width="13" height="13" align="absmiddle" />
  {end:}
  {if:widget.font}
    <font class="{widget.font}">
  {end:}
  {widget.label:h}
  {if:widget.font}
    </font>
  {end:}
</a>

{* SVN $Id$ *}

{* Breadcrumbs *}

<div class="NavigationPath">
  {* FIXME - Flexy compiler does not support the "getLocationPath().getNodes()" expression *}
  {foreach:locationPath.getNodes(),index,data}
  {if:!#0#=index}&nbsp;::&nbsp;{end:}
  {if:!locationPath.isLast(index)}<a href="{data.getLink()}" class="NavigationPath">{end:}{data.getName():h}{if:!locationPath.isLast(index)}</a>{end:}
  {end:}
</div>


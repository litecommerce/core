<p IF="moreThanOnePage" class="NavigationPath">
<table border=0 width="90%">
<tr>
<td> 
Result pages:&nbsp;{foreach:pageUrls,num,pageUrl}{if:isCurrentPage(num)}<b>[{num}]</b>{else:}<a href="{pageUrl:h}">[<u>{num}</u>]</a>{end:}&nbsp;{end:}
</td>
</tr>
</table>
</p>

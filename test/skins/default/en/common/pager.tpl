<div IF="moreThanOnePage" class="NavigationPath">
  <table width="90%">
    <tr>
      <td> 
        Result pages:&nbsp;{foreach:pageUrls,num,pageUrl}{if:isCurrentPage(num)}<b>[{num}]</b>{else:}<a href="{pageUrl}">[<u>{num}</u>]</a>{end:} {end:}
      </td>
    </tr>
  </table>
</div>

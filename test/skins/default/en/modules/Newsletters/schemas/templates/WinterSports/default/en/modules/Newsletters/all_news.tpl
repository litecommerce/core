<widget class="XLite_View_Pager" data="{allNews}" name="pager" itemsPerPage="5">

<div IF="pager.pageData">
<div FOREACH="pager.pageData,nn">

<table>
<tr><td><h4><!-- [message subject] -->{nn.subject:h}<!-- [/message subject] --></h4></td></tr>
<tr><td><!-- [message body] -->{nn.body:h}<!-- [/message body] --></td></tr>
</table>

<hr noshade>

<div align=right><i>{date_format(nn,#send_date#,#%e %b %Y#):h}</i></div>

<br><br>

</div>
</div>

<widget name="pager">

<p IF="!allNews">
There are currently no news items available.
</p>

{if:order.substatus} 
<widget class="COrderStatus" status="{order.substatus}">
{else:}
<widget class="COrderStatus" status="{order.status}">
{end:}

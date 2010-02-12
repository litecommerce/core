{if:order.substatus} 
<widget class="XLite_Module_AOM_View_OrderStatus" status="{order.substatus}">
{else:}
<widget class="XLite_Module_AOM_View_OrderStatus" status="{order.status}">
{end:}

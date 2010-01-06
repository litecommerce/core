<p FOREACH="updateErrors,error">
<font class="Star">Quantity of product '{error.name}' cannot be updated</font>, because the requested amount [{error.amount}] is out of purchase limit range ({if:error.min}min. quantity = {error.min}{end:}{if:error.max} max. quantity = {error.max}{end:}). 
</p>

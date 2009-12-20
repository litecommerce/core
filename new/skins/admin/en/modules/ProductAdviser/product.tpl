<tr>
  <td valign="middle" class="FormButton">Mark product as "New"</td>
  <td valign="middle" class=ProductDetails>
   <select name=NewArrivalStatus>
        <option value=1 selected="{isSelected(product.NewArrival,#1#)}">Default period</option>
        <option value=2 selected="{isSelected(product.NewArrival,#2#)}">Forever</option>
        <option value=0 selected="{isSelected(product.NewArrival,#0#)}">Unmark</option>
    </select> 
  </td>
</tr>

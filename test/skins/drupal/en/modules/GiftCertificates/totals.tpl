{* SVN $Id$ *}
<li IF="!cart.payedByGC=0">><em>Paid with GC:</em>
  {price_format(cart,#payedByGC#):h}
  <div><widget class="XLite_View_Button" href="{buildURL(#cart#,#remove_gc#,_ARRAY_(#return_target#^target))}" label="Remove GC"></div>
</li>

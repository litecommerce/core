<span class="item-option" FOREACH="item.productOptions,option">
  {option.class:h}: {option.option:h}<span IF="optionArrayPointer<optionArraySize">, </span>
</span>

<div class="item-change-options" IF="item.productOptions"><a href="#">Change options</a></div>

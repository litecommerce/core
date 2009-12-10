{if:order.isSelected(#status#,#Q#)}Queued{end:}
{if:order.isSelected(#status#,#P#)}Processed{end:}
{if:order.isSelected(#status#,#I#)}Incomplete{end:}
{if:order.isSelected(#status#,#F#)}Failed{end:}
{if:order.isSelected(#status#,#D#)}Declined{end:}
{if:order.isSelected(#status#,#C#)}Complete{end:}

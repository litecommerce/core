{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
{if:order.isSelected(#status#,#Q#)}{t(#Queued#)}{end:}
{if:order.isSelected(#status#,#P#)}{t(#Processed#)}{end:}
{if:order.isSelected(#status#,#I#)}{t(#Incomplete#)}{end:}
{if:order.isSelected(#status#,#F#)}{t(#Failed#)}{end:}
{if:order.isSelected(#status#,#D#)}{t(#Declined#)}{end:}
{if:order.isSelected(#status#,#C#)}{t(#Complete#)}{end:}

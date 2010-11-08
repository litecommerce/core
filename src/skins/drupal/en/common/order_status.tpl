{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order satstus label
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
{if:isSelected(order,#status#,#Q#)}{t(#Queued#)}{end:}
{if:isSelected(order,#status#,#P#)}{t(#Processed#)}{end:}
{if:isSelected(order,#status#,#I#)}{t(#Incomplete#)}{end:}
{if:isSelected(order,#status#,#F#)}{t(#Failed#)}{end:}
{if:isSelected(order,#status#,#D#)}{t(#Declined#)}{end:}
{if:isSelected(order,#status#,#C#)}{t(#Complete#)}{end:}

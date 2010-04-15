{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<script language="Javascript">
<!-- 

function visibleBox(id, status)
{
	var Element = document.getElementById(id);
    if (Element) {
    	Element.style.display = ((status) ? "" : "none");
    }
}

function editSize(opttype,updateId) 
{
	if (!updateId) {
		document.add_option_form.opttype.value = opttype;
		updateId = "";
	}
    if (opttype == "Text") {
    	visibleBox("TextareaTR"+updateId, false);
    	visibleBox("TextareaTRhead"+updateId, false);
    	visibleBox("TextTR"+updateId, true);
    	visibleBox("TextTRhead"+updateId, true);
    } else if (opttype == "Textarea") {
    	visibleBox("TextareaTR"+updateId, true);
    	visibleBox("TextareaTRhead"+updateId, true);
    	visibleBox("TextTR"+updateId, true);
    	visibleBox("TextTRhead"+updateId, false);
    } else {
    	visibleBox("TextareaTR"+updateId, false);
    	visibleBox("TextareaTRhead"+updateId, false);
    	visibleBox("TextTR"+updateId, false);
    	visibleBox("TextTRhead"+updateId, false);
    }
}

function initEditSize(updateId) 
{
	var Element = document.getElementById("opttype"+updateId);
    if (Element) {
        editSize(Element.value, updateId);
    }
}

function ShowPONotes()
{
	visibleBox("PO_Notes_URL", false);
    visibleBox("PO_Notes", true);
}

function ShowPOExamples()
{
	visibleBox("PO_Examples_URL", false);
    visibleBox("PO_Examples", true);
}

function ShowPOJSExamples()
{
	visibleBox("PO_JSExample_URL", false);
    visibleBox("PO_JSExample", true);
}

var OptionClassesArray = new Array();
var OptionClassesExludeSelectsArray = new Array();

function AddExcludeCombination()
{
	var i;
	var iMax;
	var Element;
	var optionExlude = "";
	var optionExludeNew = "";
	var addExeprion = false;

	iMax = OptionClassesArray.length;
	for (i=0; i<iMax; i++) {
    	Element = document.getElementById(OptionClassesExludeSelectsArray[i]);
        if (Element) {
        	if (Element.value.length > 0) {
            	optionExludeNew = optionExlude + OptionClassesArray[i] + "=" + Element.value;
            	if (i < (iMax-1)) {
            		optionExludeNew += ";";
            	}
            	if (optionExludeNew.length <= 255) {
    				optionExlude = optionExludeNew;
    				addExeprion = true;
            	}
        	}
        }
	}

    if (addExeprion) {
    	if (optionExlude.substring((optionExlude.length-1),optionExlude.length) == ";") {
    		optionExlude = optionExlude.substring(0,(optionExlude.length-1));
    	}
    	Element = document.getElementById("option_exlude");
        if (Element) {
        	Element.value = optionExlude;
        }

    	document.exception_form.submit();
    }
}

// -->
</script>

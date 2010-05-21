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
<script type="text/javascript" language="Javascript">
<!--

function visibleBox(id, status)
{
    var Element = document.getElementById(id);
    if (Element) {
        Element.style.display = ((status) ? "" : "none");
    }
}

function SetDateElements(dateVal,prefix,custom)
{
    var elm1 = document.getElementById("search_"+prefix+"Year");
    var elm2 = document.getElementById("search_"+prefix+"Month");
    var elm3 = document.getElementById("search_"+prefix+"Day");
    var elm4 = document.getElementById("search_"+prefix+"Raw");
    if (!(elm1 && elm2 && elm3 && elm4))
        return;

    if (!custom) {
        for(var i=0; i<elm1.length; i++) {
            if (elm1.options[i] == dateVal.getYear()) {
                elm1.selectedIndex = i;
                break;
            }
        }
    }
    elm1.disabled = (!custom) ? true : false;

    if (!custom) {
        elm2.selectedIndex = dateVal.getMonth();
    }
    elm2.disabled = (!custom) ? true : false;
    if (!custom) {
        elm3.selectedIndex = (dateVal.getDate() - 1);
    }
    elm3.disabled = (!custom) ? true : false;
    if (!custom) {
        var dateTime = new String;
        dateTime += dateVal.getTime();
        elm4.value = dateTime.substr(0,10);
    }
}

function SetMonthPeriod(previous)
{
    var currentDate = new Date();
    var startDate = new Date(currentDate.getYear(),currentDate.getMonth(),currentDate.getDate());

    previous = (previous) ? 1 : 0;
    startDate.setMonth(currentDate.getMonth()-previous);
    startDate.setDate(1);
    SetDateElements(startDate, "startDate");

    var endDate = new Date(startDate.getYear(),startDate.getMonth()+1,1);
    endDate.setDate(endDate.getDate()-1);
    SetDateElements(endDate, "endDate");
}

function SetWeekPeriod(previous)
{
    var currentDate = new Date();
    var startDate = new Date(currentDate.getYear(),currentDate.getMonth(),currentDate.getDate());

    var currentDay = currentDate.getDay();
    if (currentDay == 0) {
        currentDay = 7;
    }

    previous = (previous) ? 7 : 0;
    startDate.setDate(currentDate.getDate()-currentDay+1-previous);
    SetDateElements(startDate, "startDate");

    var endDate = new Date(startDate.getYear(),startDate.getMonth(),startDate.getDate());
    endDate.setDate(startDate.getDate()+6);
    SetDateElements(endDate, "endDate");
}

function SetDayPeriod(previous)
{
    var currentDate = new Date();
    var startDate = new Date(currentDate.getYear(),currentDate.getMonth(),currentDate.getDate());

    previous = (previous) ? 1 : 0;
    startDate.setDate(currentDate.getDate()-previous);
    SetDateElements(startDate, "startDate");

    var endDate = new Date(startDate.getYear(),startDate.getMonth(),startDate.getDate());
    SetDateElements(endDate, "endDate");
}

function SetCustomPeriod()
{
    SetDateElements(null, "startDate", true);
    SetDateElements(null, "endDate", true);
}

function SetPeriod()
{
    var Element = document.getElementById("search_period");
    if (!Element)
        return;

    if (Element.id != "search_period") {
    	var Elements = document.all["search_period"];
    	for(var i = 0; i < Elements.length; i++) {
    		if (Elements[i].id == "search_period") {
    			Element = Elements[i];
    			break;
    		}
    	}
    }
 
    var period = Element.options[Element.selectedIndex].value;
    switch (period) {
        case "-1":
        case "0":
            SetDayPeriod();
        break;
        case "1":
            SetDayPeriod(true);
        break;
        case "2":
            SetWeekPeriod();
        break;
        case "3":
            SetWeekPeriod(true);
        break;
        case "4":
            SetMonthPeriod();
        break;
        case "5":
            SetMonthPeriod(true);
        break;
        case "6":
            SetCustomPeriod();
        break;
    }
    visibleBox("custom_dates", ((period == "6")?true:false));
}

//-->
</script>

/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * States lsit controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */
function clearStates(prefix)
{
    var stateValue = "";

    var elm = document.getElementById(prefix+"_state_select");
    if (elm) 
    {
        stateValue = elm.value;

        var i, iMax;
        iMax = elm.options.length;
        for (i=(iMax-1); i >= 0; i--)
        {
            elm.remove(i);
            elm.options[i] = null;
        }
    }

    return stateValue;
}

function populateStates(country,prefix,restoreValue)
{
    var stateValue = clearStates(prefix);

    var elm = document.getElementById(prefix+"_state_select");

  if(!CountriesStates[country.value]) {
        if (elm) 
        {
      elm.options[0] = new Option("Select one...", 0);
        }

    initStates();
    return;
  }

    if (elm) 
    {
        var assignedStates = new Array();
        var i = 0;
        if (CountriesStates[country.value] && CountriesStates[country.value].length > 0)
        {
            elm.options[i++] = new Option("Select one...", 0);
      assignedStates.push(0);
        }

        elm.options[i++] = new Option("Other", -1);
    assignedStates.push(-1);

        if (CountriesStates[country.value] && CountriesStates[country.value].length > 0)
        {
            for(var j = 0; j < CountriesStates[country.value].length; j++)
            {
                if (CountriesStates[country.value][j]) {
                    elm.options[i++] = new Option(CountriesStates[country.value][j].state, CountriesStates[country.value][j].state_code);
            assignedStates.push(CountriesStates[country.value][j].state_code);
                }
            }
        }

        if (restoreValue)
        {
          var correctState = false;
          var correctStateSelectOne = false;
            for(i=0; i<assignedStates.length; i++)
            {
              if (assignedStates[i] == 0)
              {
                correctStateSelectOne = true;
              }
              if (assignedStates[i] == stateValue)
              {
                correctState = true;
                break;
              }
            }
            if (!correctState)
            {
              if (stateValue > 0)
              {
                if (correctStateSelectOne)
                {
                  stateValue = 0;
                }
                else
                {
                  stateValue = -1;
                }
              }
            else
            {
              stateValue = -1;
            }
            }

          i = 0;  
            while(elm.value != stateValue)
            {
              i ++;
                elm.value = stateValue;     // for Opera compatibility
                if (i>1000) {
                  break;
                }
            }
        }
    }

  initStates();
}

function changeState(state, prefix)
{
  obj = document.getElementById(prefix+'_custom_state_body');
  if (obj && state) {
    obj.style.display = (state.value == -1) ? "" : "none";
  }
}

/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Event task progress bar controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.22
 */

jQuery().ready(
  function () {

    // Non blocking event driver

    var initializeNextStep = function(event, data, bar, eventName)
    {
      var percent = 0;

      if (data && 'undefined' != typeof(data.percent)) {
        bar.progressbar('value', data.percent);
        bar.attr('title', data.percent + '%')
        bar.trigger('changePercent');
        percent = data.percent;
      }

      if (100 > percent) {
        core.post(
          URLHandler.buildURL({'target': 'event_task', 'action': 'run', 'event': eventName}),
          function(xhr, status, data, valid) {
            if (xhr.readyState != 4 || xhr.status != 200) {
              core.showError('Event task runner internal error');
            }
          },
          {},
          {timeout: 600000}
        );

      } else {
        if (data.error) {
          bar.trigger('error');
        }
        bar.trigger('complete');
      }
    }

    jQuery('.progress-bar.noblocking').each(
      function () {

        var eventName = jQuery(this).data('event');
        var bar = jQuery('.bar', this);

        core.bind(
          'eventTaskRun',
          function (event, data) {
            return initializeNextStep(event, data, bar, eventName);
          }
        );

        bar.progressbar();
        initializeNextStep(null, {percent: bar.data('percent')}, bar, eventName);
      }
    );

    // Blocking event driver
    var timer;
    var timerTTL = 10000;
    var updateProgressBar = function()
    {
      var o = this;
      var bar = jQuery('.bar', this);
      var eventName = jQuery(this).data('event');

      core.get(
        URLHandler.buildURL({'target': 'event_task', 'action': 'touch', 'event': eventName}),
        function(xhr, status, data) {
          if (xhr.readyState != 4 || xhr.status != 200) {
            core.showError('Event task touch procedure internal error');

          } else {
            data = jQuery.parseJSON(data);
            if (data && 'undefined' != typeof(data.percent)) {
              bar.progressbar('value', data.percent);
              bar.attr('title', data.percent + '%')
              bar.trigger('changePercent');
              if (100 > data.percent) {
                timer = setTimeout(function () { return o.updateProgressBar(); }, timerTTL);

              } else {

                if (data.error) {
                  bar.trigger('error');
                }

                bar.trigger('complete');
              }

            } else {
              core.showError('Event task touch procedure internal error');
            }
          }
        },
        {},
        {timeout: 10000}
      );
    }

    jQuery('.progress-bar.blocking').each(
      function () {

        var bar = jQuery('.bar', this);

        bar.progressbar();
        bar.progressbar('value', bar.data('percent'));

        this.updateProgressBar = updateProgressBar;
        this.updateProgressBar();
      }
    )
  }
);

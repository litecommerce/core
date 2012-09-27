/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Import / export controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

jQuery().ready(
  function() {
    jQuery('.import-export-box .import-box').eq(0).each(
      function() {

        var bar = jQuery('.bar', this);
        var timer;

        function initializeNextStep(event, data)
        {
          if (event && data && 'undefined' != typeof(data.position) && 'undefined' != typeof(data.length) && data.length) {
            bar.progressbar('value', Math.round(100 * data.position / data.length));
          }

          core.post(
            URLHandler.buildURL({target: 'import_export', action: 'import'}),
            function(xhr, status, data, valid) {
              if (xhr.readyState != 4 || xhr.status != 200 || !valid) {
                core.showError('Import internal error');
                self.location = URLHandler.buildURL({target: 'import_export', cancel_import: 1});
              }
            }
          );
        }

        function finishImport()
        {
          bar.progressbar('value', 100);
          self.location = URLHandler.buildURL({target: 'import_export'});
        }

        core.bind('importAfterStep', initializeNextStep);
        core.bind('importFinish', finishImport);

        openDialog('.import-export-box .import-box', { closeOnEscape: false });
        jQuery('.ui-dialog.popup').addClass('import-popup');
        bar.progressbar({ value: 1 });
        initializeNextStep();
      }
    );
  }
);


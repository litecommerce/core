{if:mode=#sent#}Message was sent successfully{end:}
{if:mode=#failed#}Message was NOT sent because your AntiFraud license key is expired {end:}
{if:response.result.error}AntiFraud service returns the following error : {response.result.error}{end:}
{if:response.data.check_error}AntiFraud service returns the following warning : {response.data.check_error}{end:}

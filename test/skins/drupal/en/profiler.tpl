{* Profiler template *}

<b>Total time:</b> {profiler.getTotalTime()} sec.<br>
<br>
<b>XLite startup time:</b>
PHP parser time: {profiler.parserTime} sec.,
Included files: {profiler.includedFilesCount}, 
Included files total size: {profiler.includedFilesTotal}, 
License check time: {profiler.licenseTime} sec., 
Database connect time: {profiler.dbConnectTime} sec.
<br>
<b>XLite init time:</b>
read config: {profiler.read_cfg_time} sec., 
modules manager: {profiler.mm_init_time} sec.,
session: {profiler.ss_time} sec.
<br>
<b>Xlite init total time:</b> {profiler.init_time} sec.<br>
<b>Run time:</b> {profiler.run_time} sec.<br>
<b>Init view time:</b> {profiler.view_time} sec.<br>
<b>Display time:</b> {profiler.displayTime()} sec.<br>
<br>
<b>SQL total queries:</b> {profiler.getTotalQueries()}<br>
<b>SQL total queries time:</b> {profiler.getTotalQueriesTime()} sec.<br>
<b>SQL queries statistics:</b><br>
<span FOREACH="profiler.queries,query,count">
<b>Total:</b> {count}, <b>Time:</b> {profiler.getQueryTime(query)}, <b>Query:</b> {query:h}<br>
</span>
<b>.</b>

Included file sizes: <table>{foreach:profiler.includedFiles,file} <tr><td>{file.name}</td><td>{file.size}</td></tr>{end:} </table>

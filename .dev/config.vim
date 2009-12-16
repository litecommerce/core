
" PHP documentor tags
let g:pdv_cfg_Category   = "Litecommerce"
let g:pdv_cfg_Package    = "Litecommerce"
let g:pdv_cfg_Author     = "Creative Development LLC <info@cdev.ru>"
let g:pdv_cfg_Copyright  = "Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved"
let g:pdv_cfg_License    = "http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement"
let g:pdv_cfg_Version    = "SVN: $Id$"
let g:pdv_cfg_Since      = "3.0.0 EE"
let g:pdv_cfg_Link       = "http://www.qtmsoft.com/"

let g:pdv_cfg_FileSee    = "____file_see____"
let g:pdv_cfg_ClassSee   = "____class_see____"
let g:pdv_cfg_VarSee     = "____var_see____"
let g:pdv_cfg_FuncSee    = "____func_see____"

let g:pdv_cfg_FileTitle  = "____file_title____"
let g:pdv_cfg_SubPackage = "____sub_package____"
let g:pdv_cfg_ParamComm  = "____param_comment____"

let g:pdv_cfg_vimOpts    = "// vim: set ts=4 sw=4 sts=4 et:"

let g:pdv_cfg_php4always = 1
let g:pdv_cfg_php4guess  = 0

func! ClassName()
	return substitute(substitute(substitute(expand("%:p"), '.\+src/lib/', '', 'g'), '/', '_', 'g'), '.php', '', '')
endfunc
inoremap <C-a> <C-R>=ClassName()<CR>

func! CheckCS()
	let parts = split(getcwd(), '\/')
	let path = ''
	for p in parts
    	let path = path . '/' . p
	    let $fpath = path . '/.dev/phpcs-report.sh'
    	if filereadable($fpath)
			let fp = $fpath . ' ' . expand("%:p")
			let e = system(fp)
			echo e
	    endif
	endfor
endfunc

:nmap <F10> :call CheckCS()<CR>

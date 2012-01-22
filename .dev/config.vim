function LC_SmartySettings()
    set ts=2
    set sw=2
    set sts=2
    set et

    nnoremap <C-a> :call SmartyDocFile()<CR>
endfunction

function LC_CSSSettings()
    set ts=2
    set sw=2
    set sts=2
    set et

    nnoremap <C-a> :call CSSDocFile()<CR>
endfunction

function LC_JSSettings()
    set ts=2
    set sw=2
    set sts=2
    set et

    nnoremap <C-a> :call JSDocFile()<CR>
endfunction

function LC_PHPSettings()
    nnoremap <C-a> :call LC_PhpDocFile()<CR>
    inoremap <C-a> <C-R>=NamespaceName()<CR>
    set foldenable
    set foldmethod=marker
    set foldmarker={{{,}}}
    set foldlevel=99
    set ts=4
    set sw=4
    set sts=4
    set et
endfunction

function LC_YAMLSettings()
    set sw=2
    set ts=2
    set sts=2
    set et

    nnoremap <C-a> :call YAMLDocFile()<CR>
endfunction

au Filetype smarty call LC_SmartySettings()
au Filetype php call LC_PHPSettings()
au Filetype css call LC_CSSSettings()
au Filetype javascript call LC_JSSettings()
au Filetype yaml call LC_YAMLSettings()


let g:pdv_cfg_CommentHead_Smarty = "{**"
let g:pdv_cfg_CommentTail_Smarty = " *}"

" PHP documentor tags
let g:pdv_cfg_Category   = "LiteCommerce"
let g:pdv_cfg_Author     = "Creative Development LLC <info@cdev.ru>"
let g:pdv_cfg_Copyright  = "Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved"
let g:pdv_cfg_License    = "http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)"
" let g:pdv_cfg_Version    = "GIT: $Id$"
let g:pdv_cfg_Since      = "1.0.16"
let g:pdv_cfg_Link       = "http://www.litecommerce.com/"

let g:pdv_cfg_FileSee    = "____file_see____"
let g:pdv_cfg_ClassSee   = "____class_see____"
let g:pdv_cfg_VarSee     = "____var_see____"
let g:pdv_cfg_FuncSee    = "____func_see____"

let g:pdv_cfg_FileTitle  = "____file_title____"
let g:pdv_cfg_ParamComm  = "____param_comment____"

let g:pdv_cfg_vimOpts    = "// vim: set ts=4 sw=4 sts=4 et:"

let g:pdv_cfg_php4always = 1
let g:pdv_cfg_php4guess  = 0


func! NamespaceName()
    return substitute(substitute(substitute(expand("%:p"), '.\+classes/', '', 'g'), '/', '\\', 'g'), '\\[a-zA-Z0-9]\+.php', '', '')
endfunc

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

func! SmartyDocFile()
    " Line for the comment to begin
    let commentline = 1

    let l:indent = matchstr(getline("."), '^\ *')

    exe "norm! " . commentline . "G$"

    " Local indent
    let l:txtBOL = g:pdv_cfg_BOL . indent

    exe l:txtBOL . "{* vim: set ts=2 sw=2 sts=2 et: *}" . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_EOL

    exe l:txtBOL . g:pdv_cfg_CommentHead_Smarty . g:pdv_cfg_EOL

    exe l:txtBOL . g:pdv_cfg_Commentn . g:pdv_cfg_FileTitle . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . " " . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@author    " . g:pdv_cfg_Author g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@copyright " . g:pdv_cfg_Copyright . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@license   " . g:pdv_cfg_License . g:pdv_cfg_EOL
"   exe l:txtBOL . g:pdv_cfg_Commentn . "@version   " . g:pdv_cfg_Version . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@link      " . g:pdv_cfg_Link . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@since     " . g:pdv_cfg_Since . g:pdv_cfg_EOL

    " Close the comment block.
    exe l:txtBOL . g:pdv_cfg_CommentTail_Smarty . g:pdv_cfg_EOL

    exe ":0"
    exe ":delete"

endfunc

func! YAMLDocFile()
    " Line for the comment to begin
    let commentline = 1

    let l:indent = matchstr(getline("."), '^\ *')
    let l:yamlComment = "# "

    exe "norm! " . commentline . "G$"

    " Local indent
    let l:txtBOL = g:pdv_cfg_BOL . indent

    exe l:txtBOL . l:yamlComment . "vim: set ts=2 sw=2 sts=2 et:" . g:pdv_cfg_EOL
    exe l:txtBOL . l:yamlComment . g:pdv_cfg_EOL

    exe l:txtBOL . l:yamlComment . g:pdv_cfg_FileTitle . g:pdv_cfg_EOL
    exe l:txtBOL . l:yamlComment . " " . g:pdv_cfg_EOL
    exe l:txtBOL . l:yamlComment . "@author    " . g:pdv_cfg_Author g:pdv_cfg_EOL
    exe l:txtBOL . l:yamlComment . "@copyright " . g:pdv_cfg_Copyright . g:pdv_cfg_EOL
    exe l:txtBOL . l:yamlComment . "@license   " . g:pdv_cfg_License . g:pdv_cfg_EOL
"   exe l:txtBOL . l:yamlComment . "@version   " . g:pdv_cfg_Version . g:pdv_cfg_EOL
    exe l:txtBOL . l:yamlComment . "@link      " . g:pdv_cfg_Link . g:pdv_cfg_EOL
    exe l:txtBOL . l:yamlComment . "@since     " . g:pdv_cfg_Since . g:pdv_cfg_EOL

    exe ":0"
    exe ":delete"

endfunc

func! CSSDocFile()
    " Line for the comment to begin
    let commentline = 1

    let l:indent = matchstr(getline("."), '^\ *')

    exe "norm! " . commentline . "G$"

    " Local indent
    let l:txtBOL = g:pdv_cfg_BOL . indent

    exe l:txtBOL . "/* vim: set ts=2 sw=2 sts=2 et: */" . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_EOL

    exe l:txtBOL . g:pdv_cfg_CommentHead . g:pdv_cfg_EOL

    exe l:txtBOL . g:pdv_cfg_Commentn . g:pdv_cfg_FileTitle . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . " " . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@author    " . g:pdv_cfg_Author g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@copyright " . g:pdv_cfg_Copyright . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@license   " . g:pdv_cfg_License . g:pdv_cfg_EOL
"   exe l:txtBOL . g:pdv_cfg_Commentn . "@version   " . g:pdv_cfg_Version . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@link      " . g:pdv_cfg_Link . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@since     " . g:pdv_cfg_Since . g:pdv_cfg_EOL

    " Close the comment block.
    exe l:txtBOL . g:pdv_cfg_CommentTail . g:pdv_cfg_EOL
    exe ":0"
    exe ":delete"

endfunc

func! JSDocFile()
    " Line for the comment to begin
    let commentline = 1

    let l:indent = matchstr(getline("."), '^\ *')

    exe "norm! " . commentline . "G$"

    " Local indent
    let l:txtBOL = g:pdv_cfg_BOL . indent

    exe l:txtBOL . "/* vim: set ts=2 sw=2 sts=2 et: */" . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_EOL

    exe l:txtBOL . g:pdv_cfg_CommentHead . g:pdv_cfg_EOL

    exe l:txtBOL . g:pdv_cfg_Commentn . g:pdv_cfg_FileTitle . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . " " . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@author    " . g:pdv_cfg_Author g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@copyright " . g:pdv_cfg_Copyright . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@license   " . g:pdv_cfg_License . g:pdv_cfg_EOL
"   exe l:txtBOL . g:pdv_cfg_Commentn . "@version   " . g:pdv_cfg_Version . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@link      " . g:pdv_cfg_Link . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@since     " . g:pdv_cfg_Since . g:pdv_cfg_EOL

    " Close the comment block.
    exe l:txtBOL . g:pdv_cfg_CommentTail . g:pdv_cfg_EOL
    exe ":0"
    exe ":delete"

endfunc


func! LC_PhpDocFile()
    " Line for the comment to begin
    let commentline = line (".") - 1

    let l:indent = matchstr(getline("."), '^\ *')

    exe "norm! " . commentline . "G$"

    " Local indent
    let l:txtBOL = g:pdv_cfg_BOL . indent

    exe l:txtBOL . g:pdv_cfg_vimOpts . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_EOL


    exe l:txtBOL . g:pdv_cfg_CommentHead . g:pdv_cfg_EOL

    exe l:txtBOL . g:pdv_cfg_Commentn . "LiteCommerce" . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "NOTICE OF LICENSE" . g:pdv_cfg_EOL
      exe l:txtBOL . g:pdv_cfg_Commentn . g:pdv_cfg_EOL
      exe l:txtBOL . g:pdv_cfg_Commentn . "This source file is subject to the Open Software License (OSL 3.0)" . g:pdv_cfg_EOL
      exe l:txtBOL . g:pdv_cfg_Commentn . "that is bundled with this package in the file LICENSE.txt." . g:pdv_cfg_EOL
      exe l:txtBOL . g:pdv_cfg_Commentn . "It is also available through the world-wide-web at this URL:" . g:pdv_cfg_EOL
      exe l:txtBOL . g:pdv_cfg_Commentn . "http://opensource.org/licenses/osl-3.0.php" . g:pdv_cfg_EOL
      exe l:txtBOL . g:pdv_cfg_Commentn . "If you did not receive a copy of the license and are unable to" . g:pdv_cfg_EOL
      exe l:txtBOL . g:pdv_cfg_Commentn . "obtain it through the world-wide-web, please send an email" . g:pdv_cfg_EOL
      exe l:txtBOL . g:pdv_cfg_Commentn . "to licensing@litecommerce.com so we can send you a copy immediately." . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "PHP version 5.3.0" . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@category  " . g:pdv_cfg_Category . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@author    " . g:pdv_cfg_Author g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@copyright " . g:pdv_cfg_Copyright . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@license   " . g:pdv_cfg_License . g:pdv_cfg_EOL
"   exe l:txtBOL . g:pdv_cfg_Commentn . "@version   " . g:pdv_cfg_Version . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@link      " . g:pdv_cfg_Link . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@see       " . g:pdv_cfg_FileSee . g:pdv_cfg_EOL
    exe l:txtBOL . g:pdv_cfg_Commentn . "@since     " . g:pdv_cfg_Since . g:pdv_cfg_EOL

    " Close the comment block.
    exe l:txtBOL . g:pdv_cfg_CommentTail . g:pdv_cfg_EOL

endfunc

:nmap <F10> :call CheckCS()<CR>

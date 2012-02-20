let php_sql_query=1
function My_PHPsettings()
  set ts=4
  set sw=4
  set sts=4
  compiler php
  nmap <F9> :make<CR>
  set keywordprg=":help"
  set incsearch
  set fmr={,}
  set foldmethod=marker
  set nofoldenable
endfunction

function My_YAMLSettings()
  set sw=2
  set ts=2
  set sts=2
endfunction

function My_HTMLSettings()
  set ts=2
  set sw=2
  set sts=2
  set et
endfunction

:au BufNewFile,BufRead *.html setf html

au Filetype php call My_PHPsettings()
au Filetype yaml call My_YAMLSettings()
au Filetype html call My_HTMLSettings()

:source ~/.vim/macros/php-doc.vim
function Global_PHPSettings()
  inoremap <C-x> <Esc>:call PhpDocSingle()<CR><Ins>
  nnoremap <C-x> :call PhpDocSingle()<CR>
  vnoremap <C-x> :call PhpDocRange()<CR>
  nnoremap <C-a> :call PhpDocFile()<CR>
endfunction

function Global_JSSettings()
  inoremap <C-x> <Esc>:call PhpDocSingle()<CR><Ins>
  nnoremap <C-x> :call PhpDocSingle()<CR>
  vnoremap <C-x> :call PhpDocRange()<CR>
  nnoremap <C-a> :call PhpDocFile()<CR>
endfunction

au Filetype php call Global_PHPSettings()
au Filetype javascript call Global_JSSettings()

:source ~/.vim/macros/local-config.vim


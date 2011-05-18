:set ts=4
:set shiftwidth=4
:set ai
:set incsearch
:set noai
:set fmr={,}
:set foldmethod=marker
:set nofoldenable

:set background=dark
:syn on
:au BufNewFile,BufRead	*	so ./syntax.vim

source ./php-doc.vim
inoremap <C-x> <Esc>:call PhpDocSingle()<CR><Ins>
nnoremap <C-x> :call PhpDocSingle()<CR>
vnoremap <C-x> :call PhpDocRange()<CR>
inoremap <C-a> <Esc>:call PhpDocFile()<CR><Ins>
nnoremap <C-a> :call PhpDocFile()<CR>


source ./config.vim

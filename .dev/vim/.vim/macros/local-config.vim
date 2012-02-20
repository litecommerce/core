let parts = split(getcwd(), '\/')
let path = ''
for p in parts
    let path = path . '/' . p
    let $fpath = path . '/.dev/config.vim'
    if filereadable($fpath)
		source $fpath
    endif
endfor

if exists("b:current_syntax")
	if (b:current_syntax == "c" || b:current_syntax == "cpp")

		" hilight C types like xxx_t
		syn match cType_t				"\<[a-z_0-9]\+_t\>"
		" hilight C often assigned unsigned types
		syn keyword cType_u				uchar ushort uint ulong
		syn keyword cType_u				u_char u_short u_int u_long
		" hilight TV types like TObject, etc.
		"syn match cType_TV			"T[A-Z][a-z]\+\>"

		hi link cType_t		Type
		hi link cType_u		Type
		hi link cType_TV	Type

		syn match vimJoke			"^\~$"

		"hi link vimJoke		Comment
	endif
endif

" change colors preference
if !has("gui_running")
	hi Comment		ctermfg=Blue
	hi Constant		ctermfg=Magenta
	hi Special		ctermfg=DarkRed
	hi Identifier	ctermfg=DarkCyan
	hi Statement	ctermfg=White
	hi PreProc		ctermfg=DarkMagenta
	hi Type			ctermfg=DarkGreen
	hi Number		ctermfg=Green
	hi Function		ctermfg=Cyan
else
	hi Statement	guifg=#e0e0e0
	"hi Label		guifg=					XXX links to Statement XXX
	"hi Conditional	guifg=					XXX links to Statement XXX
	"hi Repeat		guifg=					XXX links to Statement XXX
	hi Todo			guibg=#e0e000 guifg=#c06000
	"hi SpecialChar	guifg=DarkRed			XXX links to Sepcial XXX
	hi Special		guifg=#c00000
	hi String		guifg=Magenta
	hi Character	guifg=#ff40c0
	"hi Error		guifg=Green
	hi Number		guifg=#00c000
	hi Float		guifg=#00c0c0			"XXX links to Number XXX
	hi Comment		guifg=Blue
	"hi Operator	guifg=					XXX links to Statement XXX
	hi Type			guifg=#00a000
	"hi Structure	guifg=DarkGreen			XXX links to Type XXX
	"hi StorageClass	guifg=DarkGreen		XXX links to Type XXX
	"hi PreCondit	guifg=					XXX links to PreProc XXX
	"hi Include		guifg=					XXX links to PreProc XXX
	"hi Macro		guifg=					XXX links to PreProc XXX
	hi PreProc		guifg=#c020c0
endif

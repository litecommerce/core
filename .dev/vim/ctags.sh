cd `dirname $_`
cd ../..
ctags -R -f '.dev/vim/tags/php' -h '.php' --exclude='.git' --totals=yes --tag-relative=yes --PHP-kinds=+cf --regex-PHP='/abstract class ([^ ]*)/\1/c/' --regex-PHP='/interface ([^ ]*)/\1/c/' --regex-PHP='/(public |static |abstract |protected |private )+function ([^ (]*)/\2/f/' src/classes src/Includes src/lib

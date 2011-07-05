#!/bin/sh

#
# Checking if modules were modified
#

#############################################################################

#
# Show usage help
#
show_usage ()
{
	cat <<EOT
Usage: $0 [options]
  -r   revision name for comparison
  -h   this help
  
EOT
	exit 2
}


#
# Display error message and exit
#
die ()
{
	[ "x$1" != "x" ] && echo $1
	exit 2
}


# Read options
while getopts "r:h" option; do
	case $option in
		r) REVISION_FROM=$OPTARG ;;
		h) show_usage $0 ;;
	esac
done

shift $((OPTIND-1));


T=`dirname $0`
BASE_DIR=`realpath $T`

ROOT_DIR=`realpath $BASE_DIR/../../../src`


# Check parameters
if [ "x${REVISION_FROM}" = "x" ]; then
	echo "Failed: Revision is not specified";
	exit 2
fi

if [ "x${ROOT_DIR}" = "x" ]; then
	echo "Failed: Wrong root directory";
	exit 2
fi


echo -e "Thr modules changed from $REVISION_FROM to HEAD:\n"

# Generate module names list
cd $ROOT_DIR/classes/XLite/Module/CDev
MODULE_NAMES=`find . -type d -depth 1 | sed 's/\.\///'`

cd $ROOT_DIR

for i in $MODULE_NAMES; do

	# Generate list of module directories
	MODULE_PATHS=`find . -type d -name "$i" -and ! -path "*/var/run/*"`

	# Find changes
	RESULT=`git diff ${REVISION_FROM}..HEAD $MODULE_PATHS`

	# Output module name if it was changed
	if [ "$RESULT" ]; then
		echo "$i"
	fi

done


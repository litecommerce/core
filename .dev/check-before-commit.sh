cd $(dirname $0)
cd ../

for i in `svn status src/classes | grep '^[MA] ' | sed -E 's/^[A-Z] +//'`;
do
    echo 'Checking '$i...;
	.dev/phpcs-report.sh $i | grep -v '^Standard: ';
	/usr/local/php-530/bin/php -l $i | grep -v '^No syntax ';

	testPath=`echo $i | sed -E 's/src.classes.XLite.//' | sed -E 's/.php//'`;
	.dev/phpunit.sh $testPath | sed -E '/^DB |^PHPUnit |^Time: |^OK |^$|\.\.\.\.\.\.$/D'

done

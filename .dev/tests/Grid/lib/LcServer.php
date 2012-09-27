<?php
/**
 * Created by JetBrains PhpStorm.
 * User: humanoid
 * Date: 08.12.11
 * Time: 16:44
 * To change this template use File | Settings | File Templates.
 */
require_once 'Server.php';
class LcServer extends Server
{
    public function __get($name){
            switch($name){
                case 'url':
                case 'public_url':
                    return "http://" . $this->public_dns;
                case 'private_url':
                    return "http://" . $this->private_dns;
                case 'admin_url':
                    return "http://" . $this->public_dns . "/xlite/src/admin.php";
                case 'cms_url':
                    return "http://" . $this->public_dns . "/xlite_cms";
            }
            return 'blah';
        }
    function setup($app)
    {
        print PHP_EOL . "Setting up LC server..." . PHP_EOL;

        //$server = $app['cloud']->server;

        $options = $app['server_options'];

        $this->run(
            'sudo rm -rf /tmp/*;' .
                'sudo /etc/init.d/mysql restart;' .
                'sudo sed "s/_hostname_/' . $this->private_dns . '/g"  .dev/tests/config.template.php > src/etc/config.php;' .
                'sudo sed "s/_hostname_/' . $this->private_dns . '/g"  .dev/loadtests/JMeterLoadTest.template.jmx > .dev/loadtests/JMeterLoadTest.jmx;' .
                'sudo sed "s/_hostname_/' . $this->private_dns . '/g"  .dev/tests/Behat/behat.template.yml | sed "s/_screenshots_url_/' . $app['screenshots_url'] . '/g" > .dev/tests/Behat/behat.yml;' .
                'sudo /etc/init.d/lc-startup -b master-dev;' .
                'sed "s/_hostname_/' . $this->private_dns . '/g"  .dev/tests/local.template.php | sed "s/_grid_hub_/' . $app["cloud"]->hub->private_dns . '/g" | sed "s/_clients_count_/' . $app["farms_count"] . '/g" | sed "s/_screenshots_url_/' . $app['screenshots_url'] . '/g" > .dev/tests/local.php;' .
                'cd ../xlite_cms; git pull; cd modules/lc_connector; git pull; cd sites/all/themes/lc3_clean; git pull;', $options);
        print PHP_EOL . "Running dev_install";

        $code = $this->run("cd .dev; ./phpunit tests/Dev_Install.php", $options);
        //if ($code != 0)
        //  die($code);
    }

    function  test_seq($app){
        print PHP_EOL . "Run sequential tests on " . $this->public_dns . "..." . PHP_EOL;
        $code = $this->run("cd .dev; sudo ./phpunit.sh ONLYWEB", $app['server_options']);
    }

    function test_parallel($app){
        print PHP_EOL . "Run parallel tests on " . $this->public_dns . "..." .PHP_EOL;

        $options = $app['server_options'];

        $code = $this->run("cd .dev; sudo ./phpunit-parallel.php --build --clients-count " . ($app['farms_count']) . "; cat /tmp/phpunit.txt", $options);
        $this->download('/tmp/*.txt', $app['log_dir'], $options);
        //$server->download('/tmp/phpunit*.xml', $app['log_dir'], $options);
        $this->run('cd /tmp; sudo chown $USER TEST-*.xml; for f in TEST-*.xml; do mv $f phpunit.${f#TEST-}; done', $options);
        $this->download('/tmp/*.xml', $app['log_dir'], $options);
        $this->download('/tmp/*.txt', $app['log_dir'], $options);

        RemoteControl::get_screenshots($app);
    }

    function test_noweb($app){
        print PHP_EOL . 'Starting Non-web tests on ' . $this->public_dns . '...' . PHP_EOL;

        $options = $app['server_options'];

        $code = $this->run('mkdir /tmp/coverage-html; cd .dev; sudo TERM=dumb ./phpunit --coverage-clover /tmp/phpunit-clover.xml --coverage-html /tmp/coverage-html --verbose --log-junit /tmp/phpunit-noweb.xml xliteAllTests tests/AllTests.php LOCAL_TESTS,NOWEB', $options);

        $this->download('/tmp/phpunit-*.xml', $app['log_dir'], $options);
        $this->download('/tmp/coverage-html', $app['log_dir'], $options);
    }

    function test_deploy($app){
        print PHP_EOL . 'Starting deploy test on ' . $this->public_dns . '...' . PHP_EOL;
        $options = $app['server_options'];

        $this->run(
            'cd ../;' .
                'cp xlite/src/etc/config.php xlite/.dev/build/config.local.php;' .
                'cp xlite_cms/sites/default/settings.php xlite_cms/sites/default/settings.old.php;' .
                'cp xlite_cms/sites/default/default.settings.php xlite_cms/sites/default/settings.php;' .
                'mkdir xlite_cms/modules/lc_connector/litecommerce;' .
                'cp -r xlite/src/* xlite_cms/modules/lc_connector/litecommerce;' .
                'rm xlite_cms/modules/lc_connector/litecommerce/etc/config.php;' .
                'sudo chmod -R 777 .', $options);

        $code = $this->run('cd xlite/.dev; sudo ./phpunit.sh DEPLOY_DRUPAL', $options);
        RemoteControl::get_screenshots($app);
        $this->run(
            'cd ../;' .
                'sudo rm -rf xlite_cms/modules/lc_connector/litecommerce;' .
                'cp xlite_cms/sites/default/settings.old.php xlite_cms/sites/default/settings.php;' .
                'sudo chmod -R 777 .', $options);
        $this->setup($app);
    }

    function apigen($app){
        $options = $app['server_options'];
        $this->run(
            'mkdir /tmp/api; cd src;' .
                'sudo php ../.dev/build/devcode_postprocess.php;' .
                'cd ' . $app['server_htdocs'] . ';' .
                'mkdir lib;' .
                'cd lib;' .
                'git clone https://github.com/apigen/apigen.git;' .
                'cd apigen;' .
                'git submodule update --init;' .
                'cd ' . $app['server_htdocs'] . '/xlite;' .
                'sudo php ' . $app['server_htdocs'] . '/lib/apigen/apigen.php --source src/classes --config .dev/build/apigen.neon --destination /tmp/api;'
            , $options);
        $this->download('/tmp/api', $app['log_dir'], $options);
        $this->run('sudo rm -rf src/classes/api; sudo rm -rf ../lib/apigen', $options);
    }
}

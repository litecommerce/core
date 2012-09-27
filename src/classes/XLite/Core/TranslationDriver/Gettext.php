<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Core\TranslationDriver;

// Hack for windows (http://www.gnu.org/software/gettext/manual/gettext.html#fnd-3)
if (!defined('LC_MESSAGES')) {
    define('LC_MESSAGES', 1729);
}

/**
 * gettext-based driver
 *
 */
class Gettext extends \XLite\Core\TranslationDriver\ATranslationDriver
{
    const DOMAIN        = 'xlite';
    const CHARSET       = 'UTF-8';
    const SHORT_CHARSET = 'utf8';
    const CATEGORY      = 'LC_MESSAGES';


    const SERVICE_LBL   = '___service___';
    const SERVICE_VALUE = 'service';

    /**
     * Dynamic domains list
     *
     * @var array
     */
    protected $domains = array();

    /**
     * Last language code
     *
     * @var string
     */
    protected $lastLanguage = null;

    /**
     * msgfmt script path (cache)
     *
     * @var string
     */
    protected $msgfmtPath = null;

    /**
     * Translate label
     *
     * @param string $name Label name
     * @param string $code Language code
     *
     * @return string|void
     */
    public function translate($name, $code)
    {
        if ($this->lastLanguage != $code || !$this->checkCurrentLocale($code)) {
            $this->setLocale($code);
            if ($this->lastLanguage != $code) {
                $this->checkIndex($code);
            }

            $this->lastLanguage = $code;
        }

        return dgettext($this->getDomain($code), $name);
    }

    /**
     * Check - valid driver or not
     *
     * @return boolean
     */
    public function isValid()
    {
        $result = function_exists('dgettext');

        if ($result) {
            if (!file_exists(LC_DIR_LOCALE)) {
                \Includes\Utils\FileManager::mkdirRecursive(LC_DIR_LOCALE);
            }

            if (!file_exists(LC_DIR_TMP)) {
                \Includes\Utils\FileManager::mkdirRecursive(LC_DIR_TMP);
            }

            $result = file_exists(LC_DIR_LOCALE)
                && is_dir(LC_DIR_LOCALE)
                && is_readable(LC_DIR_LOCALE)
                && is_writable(LC_DIR_LOCALE)
                && file_exists(LC_DIR_TMP)
                && is_dir(LC_DIR_TMP)
                && is_readable(LC_DIR_TMP)
                && is_writable(LC_DIR_TMP);

            if ($result) {
                $label = $this->translate(
                    self::SERVICE_LBL,
                    \XLite\Core\Session::getInstance()->getLanguage()->getCode()
                );
                $result = self::SERVICE_VALUE == $label;
            }
        }

        return $result;
    }

    /**
     * Reset language driver
     *
     * @return void
     */
    public function reset()
    {
        \Includes\Utils\FileManager::unlinkRecursive(LC_DIR_LOCALE);

        $this->domains = array();
        $this->lastLanguage = null;
        $this->getRepo()->cleanCache();
    }

    /**
     * Check - current locale is indetical specified language code or not
     *
     * @param string $code Language code
     *
     * @return boolean
     */
    protected function checkCurrentLocale($code)
    {
        return getenv(self::CATEGORY) == $this->getLocaleByCode($code);
    }

    /**
     * Set current locale
     *
     * @param string $code Language code
     *
     * @return void
     */
    protected function setLocale($code)
    {
        if (defined(self::CATEGORY)) {
            $locale = $this->getLocaleByCode($code);

            putenv(self::CATEGORY . '=' . $locale);
            setlocale(constant(self::CATEGORY), $locale);
        }
    }

    /**
     * Get locale code by language code
     *
     * @param string $code Language code
     *
     * @return string
     */
    protected function getLocaleByCode($code)
    {
        return $code . '_' . strtoupper($code) . '.' . self::SHORT_CHARSET;
    }

    /**
     * Get dynamic domain name by language code
     *
     * @param string $code Language code
     *
     * @return string
     */
    protected function getDomain($code)
    {
        if (!isset($this->domains[$code])) {
            $files = glob(LC_DIR_LOCALE . LC_DS . $this->getLocaleByCode($code) . LC_DS . self::CATEGORY . LC_DS . '*.mo');
            if (is_array($files)) {
                foreach ($files as $file) {
                    $this->domains[$code] = substr(basename($file), 0, -3);
                    break;
                }
            }

            if (!isset($this->domains[$code])) {
                $this->domains[$code] = self::DOMAIN . '.' . (string)microtime(true);
            }

            bindtextdomain($this->domains[$code], LC_DIR_LOCALE);
            bind_textdomain_codeset($this->domains[$code], self::CHARSET);
        }

        return $this->domains[$code];
    }

    /**
     * Check index file (.mo file)
     *
     * @param string $code Language code
     *
     * @return boolean
     */
    protected function checkIndex($code)
    {
        $result = true;

        $path = LC_DIR_LOCALE . LC_DS . $this->getLocaleByCode($code) . LC_DS . self::CATEGORY;
        if (!file_exists($path)) {
            \Includes\Utils\FileManager::mkdirRecursive($path);
        }

        $path .= LC_DS . $this->getDomain($code) . '.mo';
        if (!file_exists($path)) {
            $result = false;
            if ($this->getMsgFmtExecutable()) {
                $result = $this->createIndexFileBin($path, $code);
            }

            if (!$result) {
                $result = $this->createIndexFile($path, $code);
            }
        }

        return $result;
    }

    /**
     * Create index file (.mo file)
     *
     * @param string $path Output path
     * @param string $code Language code
     *
     * @return boolean
     */
    protected function createIndexFile($path, $code)
    {
        $list = $this->getRepo()->findLabelsByCode($code);
        $list[self::SERVICE_LBL] = self::SERVICE_VALUE;

        $result = false;

        // .mo-file format source: http://www.gnu.org/software/gettext/manual/gettext.html#MO-Files
        $fp = @fopen($path, 'wb');
        if ($fp) {

            $n = count($list);
            $o = 28;
            $t = $o + $n * 8;
            $s = 0;
            $h = $t + $n * 8;

            // Writing the header and offsets
            fwrite($fp, pack('LLLLLLL', hexdec('950412de'), 0, $n, $o, $t, $s, $h));

            $spointer = $h + $s * 4;

            // Writing the table containing the lengths and offsets of language label names
            foreach ($list as $n => $v) {
                $l = strlen($n);
                fwrite($fp, pack('LL', $l, $spointer));
                $spointer += $l+1;
            }

            // Writing the table containing the lengths and offsets of language label values
            foreach ($list as $v) {
                $l = strlen($v);
                fwrite($fp, pack('LL', $l, $spointer));
                $spointer += $l + 1;
            }

            $nul = chr(0);

            // Writing NUL terminated language label names
            foreach ($list as $n => $v) {
                fwrite($fp, $n . $nul);
            }

            // Writing NUL terminated language label values
            foreach ($list as $v) {
                fwrite($fp, $v . $nul);
            }

            fclose($fp);

            $result = true;
        }

        return $result;
    }

    /**
     * Create index file (.mo file) with msgfmt console script
     *
     * @param string $path Output path
     * @param string $code Language code
     *
     * @return boolean
     */
    protected function createIndexFileBin($path, $code)
    {
        $list = $this->getRepo()->findLabelsByCode($code);
        $list[self::SERVICE_LBL] = self::SERVICE_VALUE;

        $result = false;

        if (!file_exists(LC_DIR_TMP)) {
            \Includes\Utils\FileManager::mkdirRecursive(LC_DIR_TMP);
        }
        $poPath = LC_DIR_TMP . 'translate.' . $code . '.po';
        $fp = @fopen($poPath, 'wb');
        if ($fp) {

            fwrite(
                $fp,
                'msgid ""' . "\n"
                . 'msgstr ""' . "\n"
                . '"Project-Id-Version: ' . \XLite::getInstance()->getVersion() . '\n"' . "\n"
                . '"PO-Revision-Date: ' . date('Y-m-d H:iO') . '\n"' . "\n"
                . '"Last-Translator: local\n"' . "\n"
                . '"Language-Team: local\n"' . "\n"
                . '"MIME-Version: 1.0\n"' . "\n"
                . '"Content-Type: text/plain; charset=UTF-8\n"' . "\n"
                . '"Content-Transfer-Encoding: 8bit\n"' . "\n"
                . "\n"
            );

            foreach ($list as $k => $v) {
                fwrite(
                    $fp,
                    'msgid "' . addcslashes($k, '"\\') . '"' . "\n"
                    . 'msgstr "' . addcslashes(str_replace("\n", '\n', $v), '"\\') . '"' . "\n\n"
                );
            }

            fclose($fp);

            $exec = $this->getMsgFmtExecutable();
            if ($exec) {
                $exec .= ' ' . $poPath . ' -o ' . $path;
                exec($exec);
//                unlink($poPath);
            }

            $result = file_exists($path);
        }

        return $result;
    }

    /**
     * Get msgfmt script path
     *
     * @return string
     */
    protected function getMsgFmtExecutable()
    {
        if (is_null($this->msgfmtPath)) {
            $this->msgfmtPath = func_find_executable('msgfmt');
        }

        return $this->msgfmtPath;
    }
}

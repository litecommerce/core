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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Model\Repo;

/**
 * Session repository
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Session extends \XLite\Model\Repo\ARepo
{
    /**
     * Public session id length
     */
    const PUBLIC_SESSION_ID_LENGTH = 32;


    /**
     * Repository type
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $type = self::TYPE_SERVICE;

    /**
     * Public session id characters list
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $chars = array(
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
        'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
        'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D',
        'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N',
        'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
        'Y', 'Z',
    );


    // {{{ Remove expired sessions

    /**
     * Find cell by session id and name
     *
     * @return \XLite\Model\SessionCell|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function removeExpired()
    {
        return $this->defineRemoveExpiredQuery()->execute();
    }

    /**
     * Define query for removeExpired() method
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineRemoveExpiredQuery()
    {
        return $this->getQueryBuilder()
            ->delete($this->_entityName, 's')
            ->andWhere('s.expiry < :time')
            ->setParameter('time', time());
    }

    // }}}

    // {{{ countBySid

    /**
     * Count session by public session id
     *
     * @param string $sid Public session id
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function countBySid($sid)
    {
        return intval($this->defineCountBySidQuery($sid)->getSingleScalarResult());
    }

    /**
     * Define query for countBySid() method
     *
     * @param string $sid Public session id
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineCountBySidQuery($sid)
    {
        return $this->createQueryBuilder('s')
            ->select('COUNT(s)')
            ->andWhere('s.sid = :sid')
            ->setParameter('sid', $sid);
    }

    // }}}

    // {{{

    /**
     * Generate public session id
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function generatePublicSessionId()
    {
        $iterationLimit = 10;
        $limit = count($this->chars) - 1;

        do {
            $x = explode('.', uniqid('', true));
            mt_srand(microtime(true) + intval(hexdec($x[0])) + $x[1]);
            $sid = '';
            for ($i = 0; self::PUBLIC_SESSION_ID_LENGTH > $i; $i++) {
                $sid .= $this->chars[mt_rand(0, $limit)];
            }
            $iterationLimit--;

        } while (0 < $this->countBySid($sid) && 0 < $iterationLimit);

        if (0 == $iterationLimit) {
            // TODO - add throw exception
        }

        return $sid;
    }

    /**
     * Check - public session id is valid or not
     *
     * @param string $sid Public session id
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isPublicSessionIdValid($sid)
    {
        static $regexp = null;

        if (!isset($regexp)) {
            $regexp = '/^[' . preg_quote(implode('', $this->chars), '/') . ']'
                . '{' . self::PUBLIC_SESSION_ID_LENGTH . '}$/Ss';
        }

        return is_string($sid) && (bool)preg_match($regexp, $sid);
    }

    // }}}
}

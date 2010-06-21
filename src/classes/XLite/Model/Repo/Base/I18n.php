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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Translations-owner abstract reporitory
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class XLite_Model_Repo_Base_I18n extends XLite_Model_Repo_AbstractRepo
{
	/**
	 * Add language subquery 
	 * 
	 * @param Doctrine\ORM\QueryBuilder $qb    Query builder
	 * @param string                    $alias Main model alias
	 * @param string                    $code  Language code
	 *  
	 * @return Doctrine\ORM\QueryBuilder
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function addLanguageQuery(Doctrine\ORM\QueryBuilder $qb, $alias, $code = null)
	{
		if (is_null($code)) {
			$code = XLite_Model_Session::getInstance()->getLanguage()->code;
		}

		$qb->add('select', 'translations', true);
		$qb->add(
			'join',
			new Doctrine\ORM\Query\Expr\Join(
				Doctrine\ORM\Query\Expr\Join::INNER_JOIN,
				$alias . '.translations',
				'translations',
				Doctrine\ORM\Query\Expr\Join::WITH,
				'translations.code = :lng'
			),
			true
		);
		$qb->setParameter('lng', $code);

		return $qb;
	}
}


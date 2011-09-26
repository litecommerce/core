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
 * @since     1.0.8
 */

namespace XLite\Core\Doctrine;

/**
 * IF(condition, then, else) MySQL function realisation
 * 
 * @see   ____class_see____
 * @since 1.0.8
 */
class IfFunction extends \Doctrine\ORM\Query\AST\Functions\FunctionNode
{
    /**
     * Parse function
     * 
     * @param \Doctrine\ORM\Query\Parser $parser Parser
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.8
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(\Doctrine\ORM\Query\Lexer::T_IDENTIFIER);
        $parser->match(\Doctrine\ORM\Query\Lexer::T_OPEN_PARENTHESIS);

        $this->ifCondition = $parser->ConditionalExpression();
        $parser->match(\Doctrine\ORM\Query\Lexer::T_COMMA);

        $this->ifThen = $parser->ScalarExpression();
        $parser->match(\Doctrine\ORM\Query\Lexer::T_COMMA);

        $this->ifElse = $parser->ScalarExpression();

        $parser->match(\Doctrine\ORM\Query\Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * Get SQL query part
     * 
     * @param \Doctrine\ORM\Query\SqlWalker $sqlWalker SQL walker
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.8
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'IF('
            . $sqlWalker->walkConditionalExpression($this->ifCondition) . ', '
            . $sqlWalker->walkSimpleArithmeticExpression($this->ifThen) . ', '
            . $sqlWalker->walkSimpleArithmeticExpression($this->ifElse) . ')';
    }

}


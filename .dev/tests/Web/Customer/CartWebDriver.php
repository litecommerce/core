<?php
/**
 * Created by JetBrains PhpStorm.
 * User: humanoid
 * Date: 17.10.11
 * Time: 14:13
 * To change this template use File | Settings | File Templates.
 */

class XLite_Web_Customer_CartWebDriver extends Xlite_WebDriverTestCase
{
    /**
     * @return XLite\Model\Product
     */
    protected function addToCart()
    {
        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')
                ->createQueryBuilder()
                ->innerJoin('p.images', 'd')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();

        $this->skipCoverage();

        $this->open('store/product//product_id-' . $product->getProductId());
        $this->get_element('xpath=//button[@class="bright add2cart"]')->click();
        $this->assert_element_present('css=.product-details .product-buttons-added .buy-more', 'check content reloading');


        /*$this->waitForLocalCondition(
            'jQuery(".product-details .product-buttons-added .buy-more").length > 0',
            10000,
            'check content reloading'
        );*/
        $this->get_element('css=.lc-minicart')->click();
        $this->get_element('css=.lc-minicart .title a')->click();
        return $product;
    }

    public function testStructure()
    {
        $product = $this->addToCart();

        $this->assert_element_present(
            "xpath=//h1[@id='page-title' and contains(text(), 'Your shopping bag') and contains(text(), '1 items')]",
            'check page title'
        );

        $cnt = count($this->get_all_elements(
                         "xpath=//div[@id='cart']"
                         . "/div[@id='shopping-cart']"
                         . "/table[@class='selected-products']"
                         . "/tbody"
                         . "/tr"
                         . "/th"
                     ));
        $this->assertEquals(4, $cnt, 'check headers count');

        $names = array('Products in bag', 'Price', 'Qty.', 'Subtotal',);
        foreach ($names as $name) {
            $this->assert_element_present(
                "xpath=//div[@id='cart']"
                . "/div[@id='shopping-cart']"
                . "/table[@class='selected-products']"
                . "/tbody"
                . "/tr"
                . "/th[text()='$name']",
                'check ' . $name . ' header'
            );
        }

        $cnt = count($this->get_all_elements(
                         "xpath=//div[@id='cart']"
                         . "/div[@id='shopping-cart']"
                         . "/table[@class='selected-products']"
                         . "/tbody"
                         . "/tr[@class='selected-product']"
                         . "/td"
                     ));
        $this->assertEquals(7, $cnt, 'check cells count');

        $cnt = count($this->get_all_elements(

                         "xpath=//div[@id='cart']"
                         . "/div[@id='shopping-cart']"
                         . "/table[@class='selected-products']"
                         . "/tbody"
                         . "/tr[@class='selected-product']"
                     ));
        $this->assertEquals(1, $cnt, 'check rows count');

        $this->assert_element_present(
            "xpath=//div[@id='cart']"
            . "/div[@id='shopping-cart']"
            . "/table[@class='selected-products']"
            . "/tbody"
            . "/tr"
            . "/td[@class='item-remove delete-from-list']"
            . "/form[@method='post']"
            . "/div"
            . "/input[@type='image' and @class='remove' and @alt='Delete item']",
            'check remove item button'
        );

        $this->assert_element_present(
            "xpath=//div[@id='cart']"
            . "/div[@id='shopping-cart']"
            . "/table[@class='selected-products']"
            . "/tbody"
            . "/tr"
            . "/td[@class='item-thumbnail']"
            . "/a"
            . "/img[@alt='" . $product->getName() . "']",
            'check thumbnail'
        );

        $this->assert_element_present(
            "xpath=//div[@id='cart']"
            . "/div[@id='shopping-cart']"
            . "/table[@class='selected-products']"
            . "/tbody"
            . "/tr"
            . "/td[@class='item-info']"
            . "/p[@class='item-title']"
            . "/a[text()='" . $product->getName() . "']",
            'check item title'
        );

        $this->assert_element_present(
            "xpath=//div[@id='cart']"
            . "/div[@id='shopping-cart']"
            . "/table[@class='selected-products']"
            . "/tbody"
            . "/tr"
            . "/td[@class='item-info']"
            . "/div[@class='item-options']"
            . "/ul[@class='selected-options']"
            . "/li"
            . "/span",
            'check item options'
        );

        $this->assert_element_present(
            "xpath=//div[@id='cart']"
            . "/div[@id='shopping-cart']"
            . "/table[@class='selected-products']"
            . "/tbody"
            . "/tr"
            . "/td[@class='item-info']"
            . "/p[@class='item-weight']"
            . "/span[text()='Weight:']",
            'check item weight'
        );

        $this->assertContains(
            $this->formatPrice($product->getPrice()),
            $this->get_element("xpath=//div[@id='cart']"
                               . "/div[@id='shopping-cart']"
                               . "/table[@class='selected-products']"
                               . "/tbody"
                               . "/tr"
                               . "/td[@class='item-price']")->get_text(),
            'check item price');

        $multi = html_entity_decode('&#215;', ENT_NOQUOTES, 'UTF-8');
        $this->assert_element_present(
            "xpath=//div[@id='cart']"
            . "/div[@id='shopping-cart']"
            . "/table[@class='selected-products']"
            . "/tbody"
            . "/tr"
            . "/td[@class='item-multi' and text()='$multi']",
            'check item multiplier symbol'
        );

        $this->assert_element_present(
            "xpath=//div[@id='cart']"
            . "/div[@id='shopping-cart']"
            . "/table[@class='selected-products']"
            . "/tbody"
            . "/tr"
            . "/td[@class='item-qty']"
            . "/form[@method='post']"
            . "/div"
            . "/span[@class='quantity-box-container']"
            . "/input[@type='text' and @value='1' and @name='amount']",
            'check item quantity'
        );

        $this->assertContains(
            $this->formatPrice($product->getPrice()),
            $this->get_element("xpath=//div[@id='cart']"
            . "/div[@id='shopping-cart']"
            . "/table[@class='selected-products']"
            . "/tbody"
            . "/tr"
            . "/td[@class='item-subtotal']"
            . "/span[@class='subtotal']")->get_text(),
            'check item subtotal');

        $this->assertContains(
            $this->formatPrice($product->getPrice()),
            $this->get_element("xpath=//div[@id='cart']"
            . "/div[@id='shopping-cart']"
            . "/table[@class='selected-products']"
            . "/tbody"
            . "/tr"
            . "/td[@class='item-subtotal']")->get_text(),
            'check item subtotal');

        
        $this->assert_element_present(
            "xpath=//div[@id='cart']"
            . "/div[@id='shopping-cart']"
            . "/div[@class='cart-buttons']"
            . "/button[@class='action']"
            . "/span[text()='Continue shopping']",
            'check Continue shopping button'
        );

        $this->assert_element_present(
            "xpath=//div[@id='cart']"
            . "/div[@id='shopping-cart']"
            . "/div[@class='cart-buttons']"
            . "/form[@method='post']"
            . "/div"
            . "/a[@class='clear-bag' and text()='Clear bag']",
            'check Clear bag link'
        );

        $this->assert_element_present(
            "xpath=//div[@id='cart']"
            . "/div[@id='shopping-cart']"
            . "/ul[@class='sums']"
            . "/li[@class='subtotal']"
            . "/strong[text()='Subtotal:']",
            'check Subtotal'
        );

        $cnt = count($this->get_all_elements(

                         "xpath=//div[@id='cart']"
                         . "/div[@id='cart-right']"
                         . "/ul[@class='totals']"
                         . "/li"
                     ));
        $this->assertEquals(4, $cnt, 'check totals rows count');

        $this->assert_element_present(
            "xpath=//div[@id='cart']"
            . "/div[@id='cart-right']"
            . "/ul[@class='totals']"
            . "/li[@class='subtotal']"
            . "/strong[text()='Subtotal:']",
            'check Subtotal #2'
        );

        $this->assert_element_present(
            "xpath=//div[@id='cart']"
            . "/div[@id='cart-right']"
            . "/ul[@class='totals']"
            . "/li[@class='order-modifier shipping-modifier']"
            . "/strong[text()='Shipping cost:']",
            'check Shipping cost'
        );

        $this->assert_element_present(
            "xpath=//div[@id='cart']"
            . "/div[@id='cart-right']"
            . "/ul[@class='totals']"
            . "/li[@class='total']"
            . "/strong[text()='Total:']",
            'check Total'
        );

        $this->assert_element_present(
            "xpath=//div[@id='cart']"
            . "/div[@id='cart-right']"
            . "/ul[@class='totals']"
            . "/li[@class='button']"
            . "/button[@class='bright']"
            . "/span[text()='Go to checkout']",
            'check Checkout button'
        );

        $this->assert_element_present(
            "xpath=//div[@id='cart']"
            . "/div[@id='cart-right']"
            . "/div[@class='box']"
            . "/div[@class='estimator']"
            . "/form[@method='get']"
            . "/div"
            . "/button",
            'check Shipping estimator button'
        );
    }

    public function testRemove()
    {
        $product = $this->addToCart();
        $this->click("xpath=//td[@class='item-remove delete-from-list']"
                     . "/form[@method='post']"
                     . "/div"
                     . "/input[@type='image']");
        $this->assert_element_present('xpath=//h1[@id="page-title" and contains(text(), "Your shopping bag is empty")]', 'check remove');
    }

    public function testUpdateQuantity()
    {

        $product = $this->addToCart();

        $qtySelector = 'td.item-qty form input[type=text]';

        $this->type(
            "//td[@class='item-qty']"
            . "/form[@method='post']"
            . "/div"
            . "/span[@class='quantity-box-container']"
            . "/input[@type='text']",
            '3'
        );

        $this->assert_element_present('id=page-title', "Element #page-title doesn't exist");
        $this->assert_element_present('xpath=//h1[@id="page-title" and contains(text(), "3 items")]', 'check quantity update');

        // Inventory tracking: check unallowed values

        $errorDivSelector = 'css=div.amount' . $product->getProductId() . 'formError';
        $errorQtySelector = 'css=td.item-qty form input.wrong-amount';
        //$qtyBlurOperation = 'jQuery("td.item-qty form input[type=text]").blur()';

        $this->type(
            "//td[@class='item-qty']"
            . "/form[@method='post']"
            . "/div"
            . "/span[@class='quantity-box-container']"
            . "/input[@type='text']",
            '-3'
        );
        $this->get_element('css=h1.title')->click();

        $this->assert_element_present($errorQtySelector, 'check minimal allowed quantity');
        $this->get_element($errorDivSelector);
        $this->assert_element_present($errorDivSelector, 'check minimal allowed quantity error');


        $this->type(
            "//td[@class='item-qty']"
            . "/form[@method='post']"
            . "/div"
            . "/span[@class='quantity-box-container']"
            . "/input[@type='text']",
            '51'
        );
        $this->get_element('css=h1.title')->click();
        $this->assert_element_present($errorQtySelector, 'check maximum allowed quantity');
        $this->assert_element_present($errorDivSelector, 'check maximum allowed quantity error');


        $this->type(
            "//td[@class='item-qty']"
            . "/form[@method='post']"
            . "/div"
            . "/span[@class='quantity-box-container']"
            . "/input[@type='text']",
            '10'
        );
        $this->get_element('css=h1.title')->click();
        //sleep(1);

        $this->assert_element_not_present($errorQtySelector, 'check normalized quantity');
        $this->assert_element_not_present($errorDivSelector, 'check normalized quantity error');
    }

    public function testEstimator()
    {
        $product = $this->addToCart();

        $this->click(
            "xpath=//div[@id='cart']"
            . "/div[@id='cart-right']"
            . "/div[@class='box']"
            . "/div[@class='estimator']"
            . "/form[@method='get']"
            . "/div"
            . "/button",
            'check open estimator'
        );

        $this->assert_element_present(
            'xpath=//h2[@class="ajax-title-loadable" and text()="Estimate shipping cost"]',
            'check open estimator (popup)');

        $this->assert_element_not_present(
            "xpath=//h3[text()='Choose shipping method']",
            "Shipping method chooser doesn't exist"
        );

        $this->assert_element_present(
            "xpath=//select[@id='destination_country']"
            . "/option[@selected='selected' and @value='US']",
            'check default country'
        );

        $this->select(
            "xpath=//select[@id='destination_country']",
            'US'
        );

        $this->type(
            "xpath=//input[@id='destination_zipcode']",
            '10001'
        );

        $this->click(
            "xpath=//form[@class='estimator']"
            . "/div"
            . "/button[@type='submit']",
            'set destination'
        );

        $this->assert_element_present(
            'xpath=//div[@class="estimate-methods"]/h3[contains(text(),"Choose shipping method")]',
            'check reload estimator (popup)'
        );

        $this->assert_element_present(
            "xpath=//h3[text()='Choose shipping method']",
            "Shipping method chooser doesn't exist"
        );

        $name = $this->get_element("xpath=//div[@class='estimate-methods']"
            . "/form[@method='post']"
            . "/ul"
            . "/li[position()=2]"
            . "/label")->get_text();

        $this->click(
            "xpath=//div[@class='estimate-methods']"
            . "/form[@method='post']"
            . "/ul"
            . "/li[position()=2]"
            . "/input[@type='radio']"
        );

        $this->click(
            "xpath=//div[@class='estimate-methods']"
            . "/form[@method='post']"
            . "/div"
            . "/button[@type='submit']"
        );


        $this->assert_element_present(
            'css=.box .estimator ul li',
            'check close estimator'
        );

        $this->get_element('css=.box .estimator ul li')->assert_text_contains($name);

        $this->assert_element_not_present(
            "xpath=//h2[text()='Estimate shipping cost']",
            "Estimete buttom doesn't exist"
        );

        $this->assert_element_present(
            "xpath=//div[@class='box']"
            . "/div[@class='estimator']"
            . "/ul"
            . "/li"
            . "/span[text()='Shipping:']",
            'check shipping box'
        );

        $this->assert_element_present(
            "xpath=//div[@class='box']"
            . "/div[@class='estimator']"
            . "/ul"
            . "/li"
            . "/span[text()='Estimated for:']",
            'check address box'
        );

        $this->get_element('css=.box .estimator ul')->assert_text_contains('United States, CA, 10001');

        $this->assert_element_present(
            "xpath=//div[@class='box']"
            . "/div[@class='estimator']"
            . "/div[@class='link']"
            . "/a[@class='estimate' and text()='Change method']",
            'check box link'
        );
    }

    public function testClear()
    {
        $product = $this->addToCart();

        $this->click(
            "xpath=//a[@class='clear-bag' and text()='Clear bag']",
            'check clear bag'
        );

        $this->assert_element_present(
            'xpath=//h1[@id="page-title" and contains(text(),"Your shopping bag is empty")]',
            'check remove'
        );
    }

    public function testContinueShopping()
    {
        $product = $this->addToCart();
        $this->click('css=#cart .cart-buttons button.action', 'click Continue shopping');
        $this->assert_element_present("css=.bright.continue", 'Not in product page');
        $this->assertRegExp('/product_id-' . $product->getProductId() . '/Ss', $this->get_url(), 'check product id');
    }

    public function testRenewByLogoff()
    {
        $this->open('user');
        $this->assert_element_present('css=#edit-name', 'Not on user page');
        $this->type('css=#edit-name', 'master');
        $this->type('css=#edit-pass', 'master');

        $this->click('css=#edit-submit');
        //$this->submitAndWait('css=#user-login');

        $product = $this->addToCart();
        $price = $product->getPrice() + 1;

        $this->open('user/logout');

        $product->setPrice($price);
        \XLite\Core\Database::getEM()->flush();

        $this->open('user');

        $this->type('css=#edit-name', 'master');
        $this->type('css=#edit-pass', 'master');

        $this->click('css=#edit-submit');

        $this->open('store/cart');

        $this->assert_element_present(
            "xpath=//div[@id='cart']"
            . "/div[@id='shopping-cart']"
            . "/table[@class='selected-products']"
            . "/tbody"
            . "/tr"
            . "/td[@class='item-price' and text()='$" . number_format(round($price, 2), 2) . "']",
            'check new item price'
        );
    }

    public function testRenewByTTL()
    {
        $product = $this->addToCart();
        $price = $product->getPrice() + 1;

        $product->setPrice($price);

        $carts = \XLite\Core\Database::getRepo('XLite\Model\Cart')->findAll();
        $cart = array_pop($carts);

        $this->assertEquals($product->getId(), $cart->getItems()->get(0)->getProduct()->getId(), 'check product id');
        $cart->setLastRenewDate($cart->getLastRenewDate() - 86400);

        \XLite\Core\Database::getEM()->flush();

        $this->open('store/cart');

        $this->assert_element_present(
            "xpath=//div[@id='cart']"
            . "/div[@id='shopping-cart']"
            . "/table[@class='selected-products']"
            . "/tbody"
            . "/tr"
            . "/td[@class='item-price' and text()='$" . number_format(round($price, 2), 2) . "']",
            'check new item price'
        );
    }

}

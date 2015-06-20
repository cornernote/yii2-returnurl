<?php
/**
 * ReturnUrlTest.php
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @link https://mrphp.com.au/
 */

namespace tests;

use cornernote\returnurl\ReturnUrl;
use Yii;

/**
 * ReturnUrlTest
 */
class ReturnUrlTest extends TestCase
{

    /**
     * Get Token
     */
    public function testGetToken()
    {
        $url = Yii::$app->request->url;
        $token = ReturnUrl::getToken();
        $tokenUrl = ReturnUrl::tokenToUrl($token);
        $this->assertEquals($url, $tokenUrl);
    }

    /**
     * Get Url
     */
    public function testGetUrl()
    {
        $url = Yii::$app->request->url;
        $token = ReturnUrl::getToken();
        $_GET['ru'] = $token;
        $returnUrl = ReturnUrl::getUrl();
        unset($_GET['ru']);
        $this->assertEquals($url, $returnUrl);
    }

    /**
     * Get Request Token from $_GET
     */
    public function testGetRequestTokenFromGet()
    {
        $token = ReturnUrl::getToken();
        $_GET['ru'] = $token;
        $requestToken = ReturnUrl::getRequestToken();
        unset($_GET['ru']);
        $this->assertEquals($token, $requestToken);
    }

    /**
     * Get Request Token from $_POST
     */
    public function testGetRequestTokenFromPost()
    {
        $token = ReturnUrl::getToken();
        $_POST['_method'] = true; // needed for Yii::$app()->request->post() to work
        $_POST['ru'] = $token;
        $requestToken = ReturnUrl::getRequestToken();
        unset($_POST['ru']);
        $this->assertEquals($token, $requestToken);
    }

    /**
     * Token as array
     */
    public function testTokenAsArray()
    {
        $_GET['ru'] = [ReturnUrl::getToken()];
        $requestToken = ReturnUrl::getRequestToken();
        unset($_GET['ru']);
        $this->assertFalse($requestToken);
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        Yii::$app->cache->flush();
    }
}
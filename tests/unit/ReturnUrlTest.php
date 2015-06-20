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
        $_POST['ru'] = $token;
        $returnUrl = ReturnUrl::getUrl();
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
        $this->assertEquals($token, $requestToken);
    }

    /**
     * Get Request Token from $_POST
     */
    public function testGetRequestTokenFromPost()
    {
        $token = ReturnUrl::getToken();
        $_POST['ru'] = $token;
        $requestToken = ReturnUrl::getRequestToken();
        $this->assertEquals($token, $requestToken);
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
<?php
/**
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @copyright 2015 Mr PHP
 * @link https://github.com/cornernote/yii2-returnurl
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii2-returnurl/master/LICENSE.md
 */

namespace cornernote\returnurl;

use Yii;
use yii\web\Request;

/**
 * Maintain state of a Return Url
 *
 * Allows the user to have multiple tabs open, each tab will handle its own
 * Return Url passed in via the GET or POST params.
 */
class ReturnUrl
{

    /**
     * @var string The key used in GET and POST requests for the Return Url.
     */
    public static $requestKey = 'ru';

    const LABEL_KEY = 'returnurl-label';

    /**
     * Get a new Token based on the current page url.
     *
     * @usage
     * in views/your_page.php
     * ```
     * echo Html::a('my link', ['test/form', 'ru' => ReturnUrl::getToken()]);
     * echo Html::hiddenInput('ru', ReturnUrl::getToken());
     * ```
     * @var string|null $label
     * @return string
     */
    public static function getToken(?string $label = null)
    {
        if (!Yii::$app->request instanceof Request) {
            return false;
        }

        $token = self::urlToToken(Yii::$app->request->url);

        if ($label) {
            self::setLabel($label, $token);
        }

        return $token;
    }

    /**
     * Save URL label in cache
     * @param string $label
     * @param string $token
     */
    public static function setLabel(string $label, string $token): void
    {
        static::cache()->set(self::LABEL_KEY . '-' . $token, $label);
    }

    /**
     * Get saved URL label form cache
     * @param string $token
     * @return mixed
     */
    public static function getLabel(string $token)
    {
        $label = static::cache()->get(self::LABEL_KEY . '-' . $token);

        return $label;
    }

    /**
     * Get the existing Token from the request data.
     *
     * @usage
     * in views/your_page.php
     * ```
     * echo Html::a('my link', ['test/form', 'ru' => ReturnUrl::getRequestToken()]);
     * echo Html::hiddenInput('ru', ReturnUrl::getRequestToken());
     * ```
     *
     * @return string|bool
     */
    public static function getRequestToken()
    {
        if (!Yii::$app->request instanceof Request) {
            return null;
        }
        $rk = self::$requestKey;
        $token = Yii::$app->request->post($rk);
        if (!$token) {
            $token = Yii::$app->request->get($rk);
        }
        if (!$token || !is_scalar($token)) {
            return null;
        }
        $token = str_replace(chr(0), '', $token); // strip nul byte
        $token = preg_replace('/\s+/', '', $token); // strip whitespace
        return $token;
    }

    /**
     * Get the URL where we should redirect to.
     *
     * @usage
     * in YourController::actionYourAction()
     * ```
     * return $this->redirect(ReturnUrl::getUrl());
     * ```
     *
     * @param mixed $altUrl alternative URL to use for redirect if there is no URL
     * @return string|bool
     */
    public static function getUrl($altUrl = null)
    {
        $url = self::tokenToUrl(self::getRequestToken());
        $url = $url ? $url : $altUrl;
        $url = $url ? $url : Yii::$app->homeUrl;
        return $url;
    }

    /**
     * Convert a URL to a Token.
     *
     * @param string $input the URL to convert
     * @return string
     */
    public static function urlToToken($input)
    {
        $key = self::khash($input);
        if (!static::cache()->exists(self::$requestKey . '.' . $key)) {
            static::cache()->set(self::$requestKey . '.' . $key, $input);
        }
        return $key;
    }

    /**
     * Convert a Token to a URL.
     *
     * @param string|bool $token the Token to convert
     * @return string|bool
     */
    public static function tokenToUrl($token)
    {
        if (!$token || !is_scalar($token)) return false;
        return static::cache()->get(self::$requestKey . '.' . $token);
    }

    /**
     * @return \yii\caching\Cache
     */
    public static function cache()
    {
        return Yii::$app->cache;
    }

    /**
     * Small sample convert crc32 to character map.
     * @link http://au1.php.net/crc32#111931
     *
     * @param string $data
     * @return string
     */
    protected static function khash($data)
    {
        static $map = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        static $hashes = [];
        if (isset($hashes[$data])) {
            return $hashes[$data];
        }
        $hash = bcadd(sprintf('%u', crc32($data)), 0x100000000);
        $str = '';
        do {
            $str = $map[(int)bcmod($hash, 62)] . $str;
            $hash = bcdiv($hash, 62);
        } while ($hash >= 1);
        return $hashes[$data] = $str;
    }

}

# Yii2 ReturnUrl

[![Latest Version](https://img.shields.io/github/tag/cornernote/yii2-returnurl.svg?style=flat-square&label=release)](https://github.com/cornernote/yii2-returnurl/tags)
[![Software License](https://img.shields.io/badge/license-BSD-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/cornernote/yii2-returnurl/master.svg?style=flat-square)](https://travis-ci.org/cornernote/yii2-returnurl)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/cornernote/yii2-returnurl.svg?style=flat-square)](https://scrutinizer-ci.com/g/cornernote/yii2-returnurl/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/cornernote/yii2-returnurl.svg?style=flat-square)](https://scrutinizer-ci.com/g/cornernote/yii2-returnurl)
[![Total Downloads](https://img.shields.io/packagist/dt/cornernote/yii2-returnurl.svg?style=flat-square)](https://packagist.org/packages/cornernote/yii2-returnurl)

ReturnUrl helper for tab-aware nested redirection in Yii2.

You might be saying, Yii2 already handles a returnUrl perfectly fine with the `Url::remember()` and `Url::previous()` methods.  Why not use those?

These methods store the returnUrl into a single variable in the users session.  This becomes a flaw when we have multiple tabs open.  Take the following scenario:

- A user navigates to a page that sets a returnUrl.  The page is for a form they have to complete.
- The phone rings, and they are required to fill in a different form.  They achieve this by opening another tab.
- As they navigate to the new page, their old returnUrl is overwritten by the new one, they complete the second form and everything seems normal.
- They then return to their first form, and after submission they get taken to the second returnUrl and their navigation path appears broken.

The solution is to pass the returnUrl into the GET and POST request by embedding it into your links and forms.  This extension makes it very easy to do and solves many common problems including the maximum length of a GET request.


## Features

- Allows a URL to be consistent with the page the user is viewing, even if they open other tabs.
- Easily embed return URLs into your links or forms.
- Handles very long returnUrl values by passing a token in the GET/POST request data.


## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
$ composer require cornernote/yii2-returnurl "*"
```

or add

```
"cornernote/yii2-returnurl": "*"
```

to the `require` section of your `composer.json` file.


## Methods

`ReturnUrl::getToken()` - Creates and returns a new token.  Pass this into the request to mark the current page as the origin url.

`ReturnUrl::getRequestToken()` - Returns the current token.  Pass this into the request to allow multiple pages to be visited before returning to the origin url.

`ReturnUrl::getUrl($altUrl)` - Return the origin url or `$altUrl` if no token was found in the request data.  Use this value to redirect to the origin url.


## Usage

Your user is on a search results page, and you have a link to an update form.  After filling in the form you want the user to be returned to the page they started from.

On the start page, add a `ReturnUrl::getToken()` to your link.  This will set the current page as the origin url.  For example in `views/post/index.php`:
```php
// generate a returnUrl link value
Html::a('edit post', ['post/update', 'id' => $post->id, 'ru' => ReturnUrl::getToken()]);
```

On the update page, add a `ReturnUrl::getRequestToken()` to your form.  This will pass the existing token through to the next page so that the controller can redirect to the origin url after it successfully saves.  For example in `views/post/update.php`:
```php
// generate a returnUrl form value
Html::hiddenInput('ru', ReturnUrl::getRequestToken());
```

In the controller action that handles the form, change the call to `$this->redirect($url)` to `$this->redirect(ReturnUrl::getUrl($url))`.  This redirects the user to the origin url.  For example in `Post::actionUpdate()`
```php
// this is where we used to redirect to, we use it as a fail-back
// (if not provided then we redirect to the home page)
$altUrl = ['post/index'];
return $this->redirect(ReturnUrl::getUrl($altUrl));
```

Is possible set label for token
```php
ReturnUrl::getToken('Back to client list');

```
Show label in back button
```php
ReturnUrl::getToken('Back to client list');
echo Html::a(ReturnUrl::getUrl($ReturnUrl::getRequestToken()),ReturnUrl::getUrl());
```


## Examples

![list](https://cloud.githubusercontent.com/assets/51875/8023635/1a1e89ba-0d53-11e5-9a1d-0f7edb45a97c.png)
![update](https://cloud.githubusercontent.com/assets/51875/8023636/1bd293c8-0d53-11e5-94c3-66fba15eff96.png)
![relations](https://cloud.githubusercontent.com/assets/51875/8023634/19eb5e50-0d53-11e5-9d3c-72cc19b06c53.png)


## License

- Author: Brett O'Donnell <cornernote@gmail.com>
- Source Code: https://github.com/cornernote/yii2-return-url
- Copyright © 2015 Mr PHP <info@mrphp.com.au>
- License: BSD-3-Clause https://raw.github.com/cornernote/yii2-return-url/master/LICENSE


## Links

- [Yii2 Extension](http://www.yiiframework.com/extension/yii2-return-url)
- [Composer Package](https://packagist.org/packages/cornernote/yii2-return-url)
- [MrPHP](http://mrphp.com.au)


[![Mr PHP](https://raw.github.com/cornernote/mrphp-assets/master/img/code-banner.png)](http://mrphp.com.au) 

# Yii2 ReturnUrl

ReturnUrl helper for recursive tab-aware redirection and recursive redirection.

[![Latest Stable Version](https://poser.pugx.org/cornernote/yii2-return-url/v/stable.png)](https://packagist.org/packages/cornernote/yii2-return-url) [![Build Status](https://travis-ci.org/cornernote/yii2-return-url.png?branch=master)](https://travis-ci.org/cornernote/yii2-return-url)


You might be saying, Yii2 already handles a returnUrl perfectly fine with the `Url::remember()` and `Url::previous()` methods.  Why not use those?

These methods store the returnUrl into a single variable in the users session.  This becomes a flaw when we have multiple tabs open.  Take the following scenario:

- A user navigates to a page that sets a returnUrl.  The page is for a form they have to complete.
- The phone rings, and they are required to fill in a different form.  They achieve this by opening another tab.
- As they navigate to the new page, their old returnUrl is overwritten by the new one, they complete the second form and everything seems normal.
- They then return to their first form, and after submission they get taken to the second returnUrl and their navigation path appears broken.

The solution is to pass the returnUrl into the GET and POST request by embedding it into your links and forms.  This extension makes it very easy to do and solves many common problems including the maximum length of a GET request.


### Contents

[Features](#features)  
[Installation](#installation)  
[Usage](#usage)  
[License](#license)  
[Links](#links) 


## Features

- Allows a URL to be consistent with the page the user is viewing, even if they open other tabs.
- Easily embed return URLs into your links or forms.
- Handles very long returnUrl values by passing a key in the GET params.


## Installation

```
composer require cornernote/yii2-return-url
```


## Usage

Your user is on a search results page, and you have a link to an update form.  After filling in the form you want the user to be returned to the page they started from.

On the start page, add a ReturnUrl to your link, for example in `views/post/index.php`:
```php
// generate a returnUrl link value
Html::a('edit post', ['post/update', 'id' => $post->id, 'ru' => ReturnUrl::getToken()]);
```

On the update page, add a returnUrl to your form, for example in `views/post/update.php`:
```php
// generate a returnUrl form value
Html::hiddenInput('ru', ReturnUrl::getRequestToken());
```

In the controller action that handles the form, change the call to `$this->redirect()`, for example in `Post::actionUpdate()`
```php
// this is where we used to redirect to, we use it as a fail-back
// (if not provided then we redirect to the home page)
$altUrl = ['post/index'];
return $this->redirect(ReturnUrl::getUrl($altUrl));
```

## Examples

![2015-06-07_2004](https://cloud.githubusercontent.com/assets/51875/8023634/19eb5e50-0d53-11e5-9d3c-72cc19b06c53.png)
![2015-06-07_1949](https://cloud.githubusercontent.com/assets/51875/8023635/1a1e89ba-0d53-11e5-9a1d-0f7edb45a97c.png)
![2015-06-07_2011](https://cloud.githubusercontent.com/assets/51875/8023636/1bd293c8-0d53-11e5-94c3-66fba15eff96.png)


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

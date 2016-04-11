# [<img alt="Vanio" src="http://www.vanio.cz/img/vanio-logo.png" width="130" align="top">](http://www.vanio.cz) Dependency Injection Extra Bundle

[![Build Status](https://travis-ci.org/vaniocz/vanio-di-extra-bundle.svg?branch=master)](https://travis-ci.org/vaniocz/vanio-di-extra-bundle)
[![Coverage Status](https://coveralls.io/repos/github/vaniocz/vanio-di-extra-bundle/badge.svg?branch=master)](https://coveralls.io/github/vaniocz/vanio-di-extra-bundle?branch=master)
![PHP7](https://img.shields.io/badge/php-7-6B7EB9.svg)
[![License](https://poser.pugx.org/vanio/vanio-di-extra-bundle/license)](https://packagist.org/packages/vanio/vanio-di-extra-bundle)

A Symfony2 Bundle providing an ability to resolve services by type by calling `getByType` on service container and lazily inject services or parameters into public properties of classes implementing `ContainerAwareInterface` (like controllers, CLI commands or Doctrine fixtures) using `Inject` annotation.
JMS DI Extra Bundle has a similar annotation but it covers much more functionality. This bundle is lightweight and does not use AOP.

# Example
```php
<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Translation\TranslatorInterface;
use Vanio\VanioDiExtraBundle\Controller;
use Vanio\VanioDiExtraBundle\DependencyInjection\Metadata\Inject;

class HelloController extends Controller
{
    /**
     * @var TranslatorInterface
     * @Inject
     */
    public $translator;
    
    /**
     * @Route("/hello", name="app_hello")
     * @Template
     */
    public function helloAction(): array
    {
        return ['message' => $this->translator->trans('Hello world!')];
    }
}
```

It is also possible to inject a service using it's ID
```php
/**
 * @Inject("translator")
 */
public $translator;
```

It is also possible to inject an optional dependency which means that it does not throw exception when the service is not found.
```php
/**
 * @Inject("translator", required=false)
 */
public $translator;
```

Injecting an optional dependency when injecting by type can be achieved using @var annotation.
```php
/**
 * @var TranslatorInterface|null
 * @Inject
 */
public $translator;
```

Injecting of container parameters is also possible
```php
/**
 * @Inject("%kernel.cache_dir%/app")
 */
public $cacheDirectory;
```

All you need to do for using the Inject annotation is to use `Vanio\VanioDiExtraBundle\DependencyInjection\ContainerAwareTrait` where you normally use the default `Symfony\Component\DependencyInjection\ContainerAwareTrait`.
There is also an abstract `Vanio\VanioDiExtraBundle\Controller` you can extend as a shortcut.

# Installation
Installation can be done as usually using composer.
`composer require vanio/vanio-di-extra-bundle`

Next step is to register this bundle inside your `AppKernel` but you also have to tell the Kernel about the new `getByType` method by overriding `getContainerBaseClass` method.
```php
// app/AppKernel.php
// ...

use Vanio\VanioDiExtraBundle\DependencyInjection\Container;

class AppKernel extends Kernel
{
    // ...

    public function registerBundles(): array
    {
        $bundles = [
            // ...
            new Vanio\VanioDiExtraBundle\VanioDiExtraBundle,
        ];

        // ...
    }

    public function getContainerBaseClass(): string
    {
        return Container::class;
    }
}
```

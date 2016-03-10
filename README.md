#Vanio Dependency Injection Extra Bundle

[![Build Status](https://api.travis-ci.org/vaniocz/vanio-di-extra-bundle.svg?branch=master)](https://travis-ci.org/vaniocz/vanio-di-extra-bundle) [![Coverage Status](https://coveralls.io/repos/github/vaniocz/vanio-di-extra-bundle/badge.svg?branch=master)](https://coveralls.io/github/vaniocz/vanio-di-extra-bundle?branch=master) [![Latest Stable Version](https://poser.pugx.org/vanio/vanio-di-extra-bundle/v/stable)](https://packagist.org/packages/vanio/vanio-di-extra-bundle) [![Total Downloads](https://poser.pugx.org/vanio/vanio-di-extra-bundle/downloads)](https://packagist.org/packages/vanio/vanio-di-extra-bundle) [![Latest Unstable Version](https://poser.pugx.org/vanio/vanio-di-extra-bundle/v/unstable)](https://packagist.org/packages/vanio/vanio-di-extra-bundle) [![License](https://poser.pugx.org/vanio/vanio-di-extra-bundle/license)](https://packagist.org/packages/vanio/vanio-di-extra-bundle)

A Symfony2 Bundle providing an ability to lazily inject services or parameters into classes implementing `ContainerAwareInterface` (like controllers, CLI commands or Doctrine fixtures) using `Inject` annotation.

#Example
```php
<?php
namespace AppBundle\Controller;

use Symfony\Component\Translation\TranslatorInterface;
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
     * @Inject(id="translator")
     */
    public $translator;
```

It is also possible to inject an optional dependency which means that it does not throw exception when the service is not found.
```php
    /**
     * @Inject(id="translator", required=false)
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
     * @Inject(parameter="kernel.cache_dir")
     */
    public $cacheDirectory;
```

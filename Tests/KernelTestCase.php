<?php
namespace Vanio\DiExtraBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as BaseKernelTestCase;
use Vanio\DiExtraBundle\DependencyInjection\Container;
use Vanio\DiExtraBundle\Tests\Fixtures\AppKernel;

abstract class KernelTestCase extends BaseKernelTestCase
{
    /** @var Container|null */
    private static $container;

    protected static function getKernelClass()
    {
        return AppKernel::class;
    }

    protected function container(): Container
    {
        if (!self::$container) {
            $this->bootKernel();
            self::$container = self::$kernel->getContainer();
        }

        return self::$container;
    }
}

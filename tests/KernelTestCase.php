<?php
namespace Vanio\VanioDiExtraBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as BaseKernelTestCase;
use Vanio\VanioDiExtraBundle\DependencyInjection\Container;
use Vanio\VanioDiExtraBundle\Tests\Fixtures\AppKernel;

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

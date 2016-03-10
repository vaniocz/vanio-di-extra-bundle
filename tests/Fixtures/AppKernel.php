<?php
namespace Vanio\VanioDiExtraBundle\Tests\Fixtures;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Vanio\VanioDiExtraBundle\DependencyInjection\Container;
use Vanio\VanioDiExtraBundle\VanioDiExtraBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as BaseAppKernel;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends BaseAppKernel
{
    public function registerBundles(): array
    {
        return [new FrameworkBundle, new VanioDiExtraBundle];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->rootDir . '/Resources/config.xml');
    }

    public function getCacheDir(): string
    {
        return sprintf('%s/vanio_di_extra_tests/cache/%s/%s', sys_get_temp_dir(), Kernel::VERSION, $this->environment);
    }

    public function getLogDir(): string
    {
        return sprintf('%s/vanio_di_extra_tests/logs/%s', sys_get_temp_dir(), Kernel::VERSION);
    }

    public function getContainerBaseClass(): string
    {
        return Container::class;
    }
}

<?php
namespace Vanio\DiExtraBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vanio\DiExtraBundle\DependencyInjection\InjectorLocatorPass;

class VanioDiExtraBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new InjectorLocatorPass);
    }
}

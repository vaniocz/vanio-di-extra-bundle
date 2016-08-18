<?php
namespace Vanio\DiExtraBundle;

use Symfony\Component\DependencyInjection\Compiler\AutowirePass as BaseAutowirePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vanio\DiExtraBundle\DependencyInjection\AutowirePass;

class VanioDiExtraBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $this->replaceAutowirePass($container);
    }

    private function replaceAutowirePass(ContainerBuilder $container)
    {
        $passConfig = $container->getCompilerPassConfig();
        $optimizationPasses = $passConfig->getOptimizationPasses();

        foreach ($optimizationPasses as &$compilerPass) {
            if ($compilerPass instanceof BaseAutowirePass) {
                $compilerPass = new AutowirePass;
            }
        }

        $passConfig->setOptimizationPasses($optimizationPasses);
    }
}

<?php
namespace Vanio\DiExtraBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\TypedReference;
use Vanio\DiExtraBundle\DependencyInjection\Metadata\MetadataFactory;
use Vanio\Stdlib\Strings;

class InjectorLocatorPass implements CompilerPassInterface
{
    /** @var ContainerBuilder */
    private $container;

    public function process(ContainerBuilder $container): void
    {
        $this->container = $container;
        $references = [];

        foreach ($this->findInjectedServices() as $service => $injectedService) {
            $invalidBehavior = $injectedService['required']
                ? ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE
                : ContainerInterface::NULL_ON_INVALID_REFERENCE;
            $references[$service] = $injectedService['id'] === null
                ? new TypedReference($injectedService['type'], $injectedService['type'], $invalidBehavior)
                : new Reference($injectedService['id'], $invalidBehavior);
        }

        $container
            ->getDefinition('vanio_di_extra.dependency_injection.injector')
            ->setArgument(1, ServiceLocatorTagPass::register($container, $references));
    }

    /**
     * @return mixed[]
     */
    private function findInjectedServices(): array
    {
        $services = [];

        foreach ($this->container->getDefinitions() as $definition) {
            if (!$class = $definition->getClass()) {
                continue;
            } elseif (Strings::contains($class, '%')) {
                $class = $this->container->getParameterBag()->resolveValue($class);
            }

            if (!$this->isContainerAware($class)) {
                continue;
            }

            $classMetadata = $this->metadataFactory()->getMetadataForClass($class);

            foreach ($classMetadata->getPropertyMetadata() as $property => $inject) {
                if (!$inject) {
                    continue;
                }

                $service = $inject->id() ?? $inject->type();

                if ($service !== null && empty($services[$service]['required'])) {
                    $services[$service] = [
                        'id' => $inject->id(),
                        'type' => $inject->type(),
                        'required' => $inject->isRequired(),
                    ];
                }
            }
        }

        return $services;
    }

    private function isContainerAware(string $class): bool
    {
        if (Strings::startsWith($class, ['Symfony\\', 'Doctrine\\'])) {
            return false;
        }

        try {
            while (is_subclass_of($class, ContainerAwareInterface::class)) {
                if (isset(class_uses($class, false)[ContainerAwareTrait::class])) {
                    return true;
                }

                $class = get_parent_class($class);
            }
        } catch (\Throwable $e) {}

        return false;
    }

    private function metadataFactory(): MetadataFactory
    {
        return $this->container->get('vanio_di_extra.dependency_injection.metadata.metadata_factory');
    }
}

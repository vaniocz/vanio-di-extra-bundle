<?php
namespace Vanio\DiExtraBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Vanio\DiExtraBundle\DependencyInjection\Metadata\MetadataFactory;

class Injector
{
    /** @var Container */
    private $container;

    /** @var ServiceLocator */
    private $serviceLocator;

    /** @var MetadataFactory */
    private $metadataFactory;

    public function __construct(Container $container, ServiceLocator $serviceLocator, MetadataFactory $metadataFactory)
    {
        $this->container = $container;
        $this->serviceLocator = $serviceLocator;
        $this->metadataFactory = $metadataFactory;
    }

    public function initializeProperties($object)
    {
        foreach (array_keys($this->metadataFactory->getMetadataForClass($object)->getPropertyMetadata()) as $property) {
            unset($object->$property);
        }
    }

    /**
     * Injects a service or parameter into the given object property.
     *
     * @param object $object
     * @param string $property
     * @throws \InvalidArgumentException
     */
    public function injectIntoProperty($object, string $property)
    {
        if (!property_exists($object, $property)) {
            throw new \InvalidArgumentException(sprintf(
                'The property %s::$%s does not exist.',
                get_class($object),
                $property
            ));
        } elseif ($inject = $this->metadataFactory->getMetadataForClass($object)->getPropertyMetadata($property)) {
            if ($parameter = $inject->parameter()) {
                $object->$property = $this->container->getParameterBag()->resolveValue($parameter);

                return;
            }

            try {
                $object->$property = $inject->id()
                    ? $this->serviceLocator->get($inject->id())
                    : $this->serviceLocator->get($inject->type());
            } catch (ServiceNotFoundException $e) {
                if ($inject->isRequired()) {
                    throw $e;
                }
            }
        } else {
            // so magic __get is not called on next try
            $object->$property = null; // @codeCoverageIgnore
        }
    }
}

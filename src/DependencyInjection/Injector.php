<?php
namespace Vanio\VanioDiExtraBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Vanio\VanioDiExtraBundle\DependencyInjection\Metadata\MetadataFactory;

class Injector
{
    /** @var Container */
    private $container;

    /** @var MetadataFactory */
    private $metadataFactory;

    /**
     * @param Container $container
     * @param MetadataFactory $metadataFactory
     */
    public function __construct(ContainerInterface $container, MetadataFactory $metadataFactory)
    {
        if (!$container instanceof Container) {
            throw new \InvalidArgumentException(sprintf(
                'Container must be an instance of "%s" class. Haven\'t you forgot to reimplement "getContainerBaseClass" inside your AppKernel class as it is described in README?',
                Container::class
            ));
        }

        $this->container = $container;
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * Unsets all properties marked for injection so lazy loading using __call is possible.
     *
     * @param object $object
     */
    public function initializeProperties($object)
    {
        foreach (array_keys($this->metadataFactory->getMetadataForClass($object)->getPropertyMetadata()) as $property) {
            unset($object->$property);
        }
    }

    /**
     * Injects a service into the given object property.
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
                $object->$property = $this->container->getParameter($parameter);

                return;
            }

            $invalidBehavior = $inject->isRequired()
                ? Container::EXCEPTION_ON_INVALID_REFERENCE
                : Container::NULL_ON_INVALID_REFERENCE;

            $object->$property = $inject->id()
                ? $this->container->get($inject->id(), $invalidBehavior)
                : $this->container->getByType($inject->type(), $invalidBehavior);
        } else {
            // so magic __get is not called on next try
            $object->$property = null;
        }
    }
}

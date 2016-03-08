<?php
namespace Vanio\VanioDiExtraBundle\DependencyInjection;

class Injector
{
    /** @var Container */
    private $container;

    /** @var MetadataFactory */
    private $metadataFactory;

    public function __construct(Container $container, MetadataFactory $metadataFactory)
    {
        $this->container = $container;
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * @param object $object
     */
    public function initializeProperties($object)
    {
        foreach (array_keys($this->metadataFactory->getMetadataForClass($object)) as $property) {
            unset($object->$property);
        }
    }

    /**
     * @param object $object
     * @param string $property
     */
    public function injectIntoProperty($object, string $property)
    {
        if ($inject = $this->metadataFactory->getInjectForProperty($object, $property)) {
            $object->$property = $inject->id()
                ? $this->container->get($inject->id())
                : $this->container->getByType($inject->type());
        }
    }
}

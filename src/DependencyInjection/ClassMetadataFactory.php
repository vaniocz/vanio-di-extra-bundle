<?php
namespace Vanio\VanioDiExtraBundle\DependencyInjection;

use Doctrine\Common\Annotations\Reader;
use Vanio\TypeParser\TypeParser;

class ClassMetadataFactory implements MetadataFactory
{
    /** @var Reader */
    private $annotationReader;

    /** @var TypeParser */
    private $typeParser;

    private $metadata = [];

    public function __construct(Reader $annotationReader, TypeParser $phpParser)
    {
        $this->annotationReader = $annotationReader;
        $this->typeParser = $phpParser;
    }

    /**
     * @param object|string $class
     * @return Metadata
     */
    public function getMetadataFor($class): Metadata
    {
        $class = is_object($class) ? get_class($class) : (string) $class;

        return $this->metadata[$class] ?? ($this->metadata[$class] = $this->loadClassMetadata($class));
    }

    public function hasMetadataFor($class): bool
    {
        return $this->getMetadataFor($class);
    }

    /**
     * @param string $class
     * @return Inject[]
     */
    private function loadClassMetadata(string $class): array
    {
        $class = new \ReflectionClass($class);
        $defaultProperties = $class->getDefaultProperties();
        $metadata = [];

        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic() && !isset($defaultProperties[$property->name])) {
                $metadata[$property->name] = $this->loadPropertyMetadata($property);
            }
        }

        return $metadata;
   }

    /**
     * @param \ReflectionProperty $property
     * @return Inject|null
     */
    private function loadPropertyMetadata(\ReflectionProperty $property)
    {
        if (!$inject = $this->annotationReader->getPropertyAnnotation($property, Inject::class)) {
            return null;
        } elseif ($inject->id() || $inject->type()) {
            return $inject;
        } elseif ($type = $this->typeParser->parsePropertyTypes($property->class)[$property->name] ?? null) {
            return Inject::byType($type);
        }

        throw new \LogicException(sprintf(
            'Cannot resolve target service for injection into %s::$%s property. Make sure it is properly annotated using @Inject and it is not missing a service ID or @var type annotation.',
            $property->class,
            $property->name
        ));
    }
}

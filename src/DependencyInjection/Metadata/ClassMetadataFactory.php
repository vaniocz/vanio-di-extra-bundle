<?php
namespace Vanio\VanioDiExtraBundle\DependencyInjection\Metadata;

use Doctrine\Common\Annotations\Reader;
use Vanio\TypeParser\Parser;

class ClassMetadataFactory implements MetadataFactory
{
    /** @var Reader */
    private $annotationReader;

    /** @var Parser */
    private $typeParser;

    /** @var array */
    private $metadata = [];

    public function __construct(Reader $annotationReader, Parser $typeParser)
    {
        $this->annotationReader = $annotationReader;
        $this->typeParser = $typeParser;
    }

    /**
     * @param object|string $class
     * @return ClassMetadata
     */
    public function getMetadataForClass($class): ClassMetadata
    {
        $class = is_object($class) ? get_class($class) : (string) $class;

        if (!isset($this->metadata[$class])) {
            $this->metadata[$class] = $this->loadClassMetadata(new \ReflectionClass($class));
        }

        return $this->metadata[$class];
    }

    private function loadClassMetadata(\ReflectionClass $class): ClassMetadata
    {
        $propertyMetadata = [];

        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $propertyMetadata[$property->name] = $this->resolvePropertyInject($property);
            }
        }

        return new ClassMetadata($class->name, $propertyMetadata);
    }

    /**
     * @param \ReflectionProperty $property
     * @return Inject|null
     */
    private function resolvePropertyInject(\ReflectionProperty $property)
    {
        if (!$inject = $this->annotationReader->getPropertyAnnotation($property, Inject::class)) {
            return null;
        } elseif ($inject->id() || $inject->parameter()) {
            return $inject;
        } elseif ($type = $this->typeParser->parsePropertyTypes($property->class)[$property->name] ?? null) {
            return Inject::byType($type->type(), !$type->isNullable());
        }

        throw new \LogicException(sprintf(
            'Cannot resolve target service for injection into %s::$%s property. Make sure it is properly annotated using @Inject and it is not missing a service ID, parameter name or @var type annotation.',
            $property->class,
            $property->name
        ));
    }
}

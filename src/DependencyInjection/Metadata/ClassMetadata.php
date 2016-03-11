<?php
namespace Vanio\VanioDiExtraBundle\DependencyInjection\Metadata;

class ClassMetadata
{
    /** @var string */
    private $name;

    /** @var Inject[] */
    private $propertyMetadata = [];

    /**
     * @param string $name
     * @param Inject[] $propertyMetadata
     */
    public function __construct(string $name, array $propertyMetadata = [])
    {
        $this->name = $name;
        $this->propertyMetadata = $propertyMetadata;
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @param string $property
     * @return Inject[]|Inject|null
     */
    public function getPropertyMetadata(string $property = null)
    {
        return $property === null ? $this->propertyMetadata : $this->propertyMetadata[$property] ?? null;
    }
}

<?php
namespace Vanio\VanioDiExtraBundle\DependencyInjection\Metadata;

interface MetadataFactory
{
    /**
     * @param object|string $class
     * @return ClassMetadata
     */
    public function getMetadataForClass($class): ClassMetadata;
}

<?php
namespace Vanio\VanioDiExtraBundle\DependencyInjection\Metadata;

interface MetadataFactory
{
    /**
     * @param object|string $class
     * @return ClassMetadata
     */
    function getMetadataForClass($class): ClassMetadata;
}

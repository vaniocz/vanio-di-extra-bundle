<?php
namespace Vanio\DiExtraBundle\DependencyInjection\Metadata;

interface MetadataFactory
{
    /**
     * @param object|string $class
     * @return ClassMetadata
     */
    function getMetadataForClass($class): ClassMetadata;
}

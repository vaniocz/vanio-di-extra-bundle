<?php
namespace Vanio\VanioDiExtraBundle\DependencyInjection;

interface MetadataFactory
{
    public function getMetadataFor($value): Metadata;

    public function hasMetadataFor($value): bool;
}

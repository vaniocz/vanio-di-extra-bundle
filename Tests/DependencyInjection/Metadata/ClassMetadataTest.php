<?php
namespace Vanio\DiExtraBundle\Tests\DependencyInjection\Metadata;

use Vanio\DiExtraBundle\DependencyInjection\Metadata\ClassMetadata;
use Vanio\DiExtraBundle\DependencyInjection\Metadata\Inject;
use Vanio\DiExtraBundle\Tests\Fixtures\Foo;

class ClassMetadataTest extends \PHPUnit_Framework_TestCase
{
    function test_name_can_be_obtained()
    {
        $classMetadata = new ClassMetadata(Foo::class);
        $this->assertSame(Foo::class, $classMetadata->name());
    }

    function test_property_metadata_can_be_obtained()
    {
        $inject = Inject::byId('id');
        $propertyMetadata = ['service' => $inject];
        $classMetadata = new ClassMetadata(Foo::class, $propertyMetadata);

        $this->assertSame($propertyMetadata, $classMetadata->getPropertyMetadata());
        $this->assertSame($inject, $classMetadata->getPropertyMetadata('service'));
    }
}

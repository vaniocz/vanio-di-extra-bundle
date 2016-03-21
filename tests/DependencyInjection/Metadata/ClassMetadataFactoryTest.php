<?php
namespace Vanio\VanioDiExtraBundle\Tests\DependencyInjection\Metadata;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Vanio\TypeParser\TypeParser;
use Vanio\VanioDiExtraBundle\DependencyInjection\Metadata\ClassMetadataFactory;
use Vanio\VanioDiExtraBundle\Tests\Fixtures\Bar;
use Vanio\VanioDiExtraBundle\Tests\Fixtures\Baz;
use Vanio\VanioDiExtraBundle\Tests\Fixtures\Foo;

class ClassMetadataFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var ClassMetadataFactory */
    private $classMetadataFactory;

    protected function setUp()
    {
        AnnotationRegistry::registerFile(__DIR__ . '/../../../src/DependencyInjection/Metadata/Inject.php');
        $typeParser = new TypeParser;
        $this->classMetadataFactory = new ClassMetadataFactory(new AnnotationReader, $typeParser);
    }

    function test_metadata_for_class_can_be_obtained()
    {
        $classMetadata = $this->classMetadataFactory->getMetadataForClass(Foo::class);

        $inject = $classMetadata->getPropertyMetadata('service');
        $this->assertSame('vanio_di_extra.tests.foo', $inject->id());
        $this->assertTrue($inject->isRequired());

        $inject = $classMetadata->getPropertyMetadata('optionalService');
        $this->assertSame('vanio_di_extra.tests.optional_service', $inject->id());
        $this->assertFalse($inject->isRequired());

        $inject = $classMetadata->getPropertyMetadata('autowiredService');
        $this->assertSame(Foo::class, $inject->type());
        $this->assertTrue($inject->isRequired());

        $inject = $classMetadata->getPropertyMetadata('optionalAutowiredService');
        $this->assertSame(Foo::class, $inject->type());
        $this->assertFalse($inject->isRequired());

        $inject = $classMetadata->getPropertyMetadata('extendedService');
        $this->assertSame('vanio_di_extra.tests.foo', $inject->id());
        $this->assertTrue($inject->isRequired());

        $inject = $classMetadata->getPropertyMetadata('extendedAutowiredService');
        $this->assertSame(Foo::class, $inject->type());
        $this->assertTrue($inject->isRequired());

        $inject = $classMetadata->getPropertyMetadata('parameter');
        $this->assertSame('%vanio_di_extra.tests.parameter%/foo', $inject->parameter());

        $inject = $classMetadata->getPropertyMetadata('parameters');
        $this->assertSame('%vanio_di_extra.tests.parameters%', $inject->parameter());

        $this->assertNull($classMetadata->getPropertyMetadata('none'));

        $this->assertNull($classMetadata->getPropertyMetadata('privateProperty'));
    }

    function test_metadata_for_child_class_can_be_obtained()
    {
        $classMetadata = $this->classMetadataFactory->getMetadataForClass(Bar::class);

        $inject = $classMetadata->getPropertyMetadata('service');
        $this->assertSame('vanio_di_extra.tests.foo', $inject->id());
        $this->assertTrue($inject->isRequired());

        $inject = $classMetadata->getPropertyMetadata('optionalService');
        $this->assertSame('vanio_di_extra.tests.optional_service', $inject->id());
        $this->assertFalse($inject->isRequired());

        $inject = $classMetadata->getPropertyMetadata('autowiredService');
        $this->assertSame(Foo::class, $inject->type());
        $this->assertTrue($inject->isRequired());

        $inject = $classMetadata->getPropertyMetadata('optionalAutowiredService');
        $this->assertSame(Foo::class, $inject->type());
        $this->assertFalse($inject->isRequired());

        $inject = $classMetadata->getPropertyMetadata('extendedService');
        $this->assertSame('vanio_di_extra.tests.foo', $inject->id());
        $this->assertFalse($inject->isRequired());

        $inject = $classMetadata->getPropertyMetadata('extendedAutowiredService');
        $this->assertSame(Foo::class, $inject->type());
        $this->assertFalse($inject->isRequired());

        $inject = $classMetadata->getPropertyMetadata('parameter');
        $this->assertSame('%vanio_di_extra.tests.parameter%/foo', $inject->parameter());
    }

    function test_metadata_for_class_with_incorrectly_annotated_property_cannot_be_obtained()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(sprintf(
            'Cannot resolve target service for injection into %s::$%s property.',
            Baz::class,
            'invalid'
        ));
        $this->classMetadataFactory->getMetadataForClass(Baz::class);
    }
}

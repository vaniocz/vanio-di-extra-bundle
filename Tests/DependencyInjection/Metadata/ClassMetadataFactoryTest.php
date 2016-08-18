<?php
namespace Vanio\DiExtraBundle\Tests\DependencyInjection\Metadata;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Vanio\DiExtraBundle\DependencyInjection\Metadata\ClassMetadataFactory;
use Vanio\DiExtraBundle\Tests\Fixtures\Bar;
use Vanio\DiExtraBundle\Tests\Fixtures\Baz;
use Vanio\DiExtraBundle\Tests\Fixtures\Foo;
use Vanio\TypeParser\TypeParser;

class ClassMetadataFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var ClassMetadataFactory */
    private $classMetadataFactory;

    protected function setUp()
    {
        AnnotationRegistry::registerFile(__DIR__ . '/../../../DependencyInjection/Metadata/Inject.php');
        $this->classMetadataFactory = new ClassMetadataFactory(new AnnotationReader, new TypeParser);
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

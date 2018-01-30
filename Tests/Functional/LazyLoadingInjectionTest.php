<?php
namespace Vanio\DiExtraBundle\Tests\DependencyInjection\Metadata;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Vanio\DiExtraBundle\DependencyInjection\Container;
use Vanio\DiExtraBundle\DependencyInjection\Injector;
use Vanio\DiExtraBundle\DependencyInjection\Metadata\MetadataFactory;
use Vanio\DiExtraBundle\DependencyInjection\ServiceForTypeNotFound;
use Vanio\DiExtraBundle\Tests\Fixtures\Baz;
use Vanio\DiExtraBundle\Tests\Fixtures\Foo;
use Vanio\DiExtraBundle\Tests\Fixtures\Qux;
use Vanio\DiExtraBundle\Tests\KernelTestCase;

class LazyLoadingInjectionTest extends KernelTestCase
{
    function test_setting_incorrect_instance_of_container_inside_container_aware()
    {
        $container = $this->createMock(ContainerInterface::class);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Container must be an instance of "%s" class.', Container::class));

        (new Foo)->setContainer($container);
    }

    function test_it_is_not_possible_to_instantiate_injector_using_incorrect_container_instance()
    {
        $container = $this->createMock(ContainerInterface::class);
        $metadataFactory = $this->createMock(MetadataFactory::class);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Container must be an instance of "%s" class.', Container::class));

        new Injector($container, $metadataFactory);
    }

    function test_it_lazily_injects_services_by_id()
    {
        $this->assertInstanceOf(Foo::class, $this->foo()->service);
    }

    function test_it_lazily_injects_services_using_autowiring()
    {
        $this->assertInstanceOf(Foo::class, $this->foo()->autowiredService);
        $this->assertInstanceOf(Foo::class, $this->foo()->optionalAutowiredService);
    }

    function test_it_lazily_injects_parameters()
    {
        $this->assertSame('parameter/foo', $this->foo()->parameter);
        $this->assertSame(['parameter'], $this->foo()->parameters);
    }

    function test_it_skips_injecting_missing_optional_dependency()
    {
        $this->assertNull($this->foo()->optionalService);
    }

    function test_it_does_not_inject_into_private_properties()
    {
        $this->assertNull($this->foo()->privateProperty());
    }

    function test_it_cannot_inject_private_service()
    {
        $this->expectException(ServiceNotFoundException::class);
        $this->expectExceptionMessageRegExp('~You have requested a non-existent service "vanio_di_extra\.tests\.private_service"\.|The "vanio_di_extra\.tests\.private_service" service or alias has been removed or inlined when the container was compiled\.~');
        $this->foo()->privateService;
    }

    function test_it_cannot_inject_private_service_using_autowiring()
    {
        $this->expectException(ServiceForTypeNotFound::class);
        $this->expectExceptionMessage(sprintf(
            'You have requested a service for non-resolvable type "%s".',
            Baz::class
        ));
        $this->foo()->autowiredPrivateService;
    }

    function test_it_can_inject_private_service_using_autowiring_of_public_alias()
    {
        $this->assertInstanceOf(Qux::class, $this->foo()->autowiredPrivateServiceUsingPublicAlias);
    }

    function test_it_cannot_inject_into_non_existent_properties()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('The property %s::$nonExistentProperty does not exist.', Foo::class));
        $this->foo()->nonExistentProperty;
    }

    private function foo(): Foo
    {
        return $this->container()->get('vanio_di_extra.tests.foo');
    }
}

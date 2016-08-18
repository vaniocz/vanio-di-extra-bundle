<?php
namespace Vanio\DiExtraBundle\Tests\DependencyInjection\Metadata;

use Vanio\DiExtraBundle\DependencyInjection\Container;
use Vanio\DiExtraBundle\DependencyInjection\ServiceForTypeNotFound;
use Vanio\DiExtraBundle\Tests\Fixtures\Bar;
use Vanio\DiExtraBundle\Tests\Fixtures\Foo;
use Vanio\DiExtraBundle\Tests\KernelTestCase;

class ContainerTest extends KernelTestCase
{
    function test_container_is_able_to_resolve_service_by_type()
    {
        $this->assertInstanceOf(Container::class, $this->container());
        $this->assertInstanceOf(Foo::class, $this->container()->getByType(Foo::class));
    }

    function test_container_is_able_to_skip_resolving_missing_optional_dependency_by_type()
    {
        $this->assertInstanceOf(Container::class, $this->container());
        $this->assertNull($this->container()->getByType(Bar::class, Container::NULL_ON_INVALID_REFERENCE));
    }

    function test_container_cannot_resolve_missing_required_dependency_by_type()
    {
        $this->assertInstanceOf(Container::class, $this->container());
        $this->expectException(ServiceForTypeNotFound::class);
        $this->expectExceptionMessage(sprintf(
            'You have requested a service for non-resolvable type "%s".',
            Bar::class
        ));
        $this->container()->getByType(Bar::class);
    }
}

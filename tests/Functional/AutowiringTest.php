<?php
namespace Vanio\VanioDiExtraBundle\Tests\DependencyInjection\Metadata;

use Vanio\VanioDiExtraBundle\Tests\Fixtures\AutowiredServices;
use Vanio\VanioDiExtraBundle\Tests\Fixtures\Foo;
use Vanio\VanioDiExtraBundle\Tests\KernelTestCase;

class AutowiringTest extends KernelTestCase
{
    function test_autowiring()
    {
        $this->assertInstanceOf(Foo::class, $this->autowiredServices()->foo);
    }

    private function autowiredServices(): AutowiredServices
    {
        return $this->container()->get('vanio_di_extra.tests.autowired_services');
    }
}

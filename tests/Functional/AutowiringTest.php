<?php
namespace Vanio\DiExtraBundle\Tests\DependencyInjection\Metadata;

use Vanio\DiExtraBundle\DependencyInjection\Metadata\CachingMetadataFactory;
use Vanio\DiExtraBundle\Tests\Fixtures\AutowiredServices;
use Vanio\DiExtraBundle\Tests\Fixtures\Foo;
use Vanio\DiExtraBundle\Tests\KernelTestCase;
use Vanio\TypeParser\CachingParser;

class AutowiringTest extends KernelTestCase
{
    function test_autowiring()
    {
        $this->assertInstanceOf(CachingParser::class, $this->autowiredServices()->typeParser);
        $this->assertInstanceOf(CachingMetadataFactory::class, $this->autowiredServices()->metadataFactory);
        $this->assertInstanceOf(Foo::class, $this->autowiredServices()->foo);
    }

    private function autowiredServices(): AutowiredServices
    {
        return $this->container()->get('vanio_di_extra.tests.autowired_services');
    }
}

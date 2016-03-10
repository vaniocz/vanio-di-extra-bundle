<?php
namespace Vanio\VanioDiExtraBundle\Tests\DependencyInjection\Metadata;

use Doctrine\Common\Cache\ArrayCache;
use Vanio\VanioDiExtraBundle\DependencyInjection\Metadata\CachingMetadataFactory;
use Vanio\VanioDiExtraBundle\DependencyInjection\Metadata\ClassMetadata;
use Vanio\VanioDiExtraBundle\DependencyInjection\Metadata\ClassMetadataFactory;
use Vanio\VanioDiExtraBundle\Tests\Fixtures\Foo;

class CachingMetadataFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var ClassMetadata */
    private $classMetadata;

    /** @var ArrayCache */
    private $cache;

    /** @var ClassMetadataFactory|\PHPUnit_Framework_MockObject_MockObject */
    private $classMetadataFactory;

    protected function setUp()
    {
        $this->classMetadata = new ClassMetadata(Foo::class);
        $this->classMetadataFactory = $this->getMockWithoutInvokingTheOriginalConstructor(ClassMetadataFactory::class);
        $this->classMetadataFactory->expects($this->once())
            ->method('getMetadataForClass')
            ->with(Foo::class)
            ->willReturn($this->classMetadata);
        $this->cache = new ArrayCache;
    }

    function test_metadata_for_class_can_be_obtained()
    {
        $cachingMetadataFactory = new CachingMetadataFactory($this->classMetadataFactory, $this->cache);
        $this->assertSame($this->classMetadata, $cachingMetadataFactory->getMetadataForClass(Foo::class));
        $namespace = $this->cache->getNamespace();
        $this->assertRegExp('~Foo\.php\[\d+\]$~', $namespace);

        $cachingMetadataFactory = new CachingMetadataFactory($this->classMetadataFactory, $this->cache);
        $this->assertSame($this->classMetadata, $cachingMetadataFactory->getMetadataForClass(Foo::class));
        $this->assertSame($namespace, $this->cache->getNamespace());
        $this->assertSame($this->classMetadata, $cachingMetadataFactory->getMetadataForClass(Foo::class));

        $this->assertSame(1, $this->cache->getStats()['hits']);
    }

    function test_metadata_for_class_can_be_obtained_without_cache_invalidation()
    {
        $cachingMetadataFactory = new CachingMetadataFactory($this->classMetadataFactory, $this->cache, false);
        $this->assertSame($this->classMetadata, $cachingMetadataFactory->getMetadataForClass(Foo::class));
        $this->assertEmpty($this->cache->getNamespace());

        $cachingMetadataFactory = new CachingMetadataFactory($this->classMetadataFactory, $this->cache, false);
        $this->assertSame($this->classMetadata, $cachingMetadataFactory->getMetadataForClass(Foo::class));

        $this->assertSame(1, $this->cache->getStats()['hits']);
    }
}

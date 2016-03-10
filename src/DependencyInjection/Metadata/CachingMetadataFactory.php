<?php
namespace Vanio\VanioDiExtraBundle\DependencyInjection\Metadata;

use Doctrine\Common\Cache\CacheProvider;

class CachingMetadataFactory implements MetadataFactory
{
    /** @var MetadataFactory */
    private $metadataFactory;

    /** @var CacheProvider */
    private $cache;

    /** @var array */
    private $metadata;

    /** @var bool */
    private $debug;

    /**
     * @param MetadataFactory $metadataFactory
     * @param CacheProvider $cache
     * @param bool $debug Whether to invalidate cache on file change (slower)
     */
    public function __construct(MetadataFactory $metadataFactory, CacheProvider $cache, bool $debug = true)
    {
        $this->metadataFactory = $metadataFactory;
        $this->cache = $cache;
        $this->debug = $debug;
    }

    /**
     * @param object|string $class
     * @return ClassMetadata
     */
    public function getMetadataForClass($class): ClassMetadata
    {
        $class = is_object($class) ? get_class($class) : (string) $class;

        if (!isset($this->metadata[$class])) {
            if ($this->debug) {
                $this->cache->setNamespace($this->resolveCacheNamespace(new \ReflectionClass($class)));
            }

            if (!$metadata = $this->cache->fetch($class)) {
                $metadata = $this->metadataFactory->getMetadataForClass($class);
                $this->cache->save($class, $metadata);
            }

            $this->metadata[$class] = $metadata;
        }

        return $this->metadata[$class];
    }

    /**
     * @param \ReflectionClass $class
     * @return int|bool
     */
    private function resolveCacheNamespace(\ReflectionClass $class)
    {
        $file = preg_replace('~\(\d+\) : eval\(\)\'d code$~', '', $class->getFileName());

        return sprintf('%s[%d]', $file, @filemtime($file));
    }
}

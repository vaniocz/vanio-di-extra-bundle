<?php
namespace Vanio\VanioDiExtraBundle\DependencyInjection\Metadata;

use Doctrine\Common\Cache\Cache;

class CachingMetadataFactory implements MetadataFactory
{
    /** @var MetadataFactory */
    private $metadataFactory;

    /** @var Cache */
    private $cache;

    /** @var array */
    private $metadata;

    /** @var bool */
    private $debug;

    /**
     * @param MetadataFactory $metadataFactory
     * @param Cache $cache
     * @param bool $debug Whether to invalidate cache on file change (slower)
     */
    public function __construct(MetadataFactory $metadataFactory, Cache $cache, bool $debug = true)
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
            $cacheId = $this->resolveCacheId($class);

            if (!$metadata = $this->cache->fetch($cacheId)) {
                $metadata = $this->metadataFactory->getMetadataForClass($class);
                $this->cache->save($cacheId, $metadata);
            }

            $this->metadata[$class] = $metadata;
        }

        return $this->metadata[$class];
    }

    /**
     * @param string $class
     * @return string
     */
    private function resolveCacheId(string $class): string
    {
        if (!$this->debug) {
            return sprintf('%s[%s]', __CLASS__, $class);
        }

        $reflectionClass = new \ReflectionClass($class);
        $file = preg_replace('~\(\d+\) : eval\(\)\'d code$~', '', $reflectionClass->getFileName());
        $modificationTimes = [];

        do {
            $modificationTimes[] = @filemtime($reflectionClass->getFileName());
        } while ($reflectionClass = $reflectionClass->getParentClass());

        return sprintf('%s[%s][%s][%s]', __CLASS__, $file, implode(',', $modificationTimes), $class);
    }
}

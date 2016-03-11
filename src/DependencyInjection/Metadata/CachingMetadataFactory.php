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
     * @return int|bool
     */
    private function resolveCacheId(string $class)
    {
        if ($this->debug) {
            $file = preg_replace('~\(\d+\) : eval\(\)\'d code$~', '', (new \ReflectionClass($class))->getFileName());
            $namespace = sprintf('%s[%s][%d]', __CLASS__, $file, @filemtime($file));
        } else {
            $namespace = __CLASS__;
        }

        return sprintf('%s[%s]', $namespace, $class);
    }
}

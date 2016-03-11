<?php
namespace Vanio\VanioDiExtraBundle\Tests\Fixtures;

use Vanio\TypeParser\Parser;
use Vanio\VanioDiExtraBundle\DependencyInjection\Metadata\MetadataFactory;

class AutowiredServices
{
    /** @var Parser|null */
    public $typeParser;

    /** @var MetadataFactory|null */
    public $metadataFactory;

    /** @var Foo|null */
    public $foo;

    public function __construct(Parser $typeParser = null, MetadataFactory $metadataFactory = null, Foo $foo = null)
    {
        $this->typeParser = $typeParser;
        $this->metadataFactory = $metadataFactory;
        $this->foo = $foo;
    }
}

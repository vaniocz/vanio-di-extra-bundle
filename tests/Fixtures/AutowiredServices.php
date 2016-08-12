<?php
namespace Vanio\DiExtraBundle\Tests\Fixtures;

use Vanio\DiExtraBundle\DependencyInjection\Metadata\MetadataFactory;
use Vanio\TypeParser\Parser;

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

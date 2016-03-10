<?php
namespace Vanio\VanioDiExtraBundle\Tests\Fixtures;

use Vanio\VanioDiExtraBundle\DependencyInjection\Metadata\MetadataFactory;

class AutowiredServices
{
    /** @var MetadataFactory */
    public $metadataFactory;

    /** @var Foo */
    public $foo;

    public function __construct(MetadataFactory $metadataFactory, Foo $foo)
    {
        $this->metadataFactory = $metadataFactory;
        $this->foo = $foo;
    }
}

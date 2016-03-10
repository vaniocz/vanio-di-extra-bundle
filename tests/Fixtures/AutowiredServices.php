<?php
namespace Vanio\VanioDiExtraBundle\Tests\Fixtures;

class AutowiredServices
{
    /** @var Foo */
    public $foo;

    public function __construct(Foo $foo)
    {
        $this->foo = $foo;
    }
}

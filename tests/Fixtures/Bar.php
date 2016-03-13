<?php
namespace Vanio\VanioDiExtraBundle\Tests\Fixtures;

use Vanio\VanioDiExtraBundle\DependencyInjection\Metadata\Inject;

class Bar extends Foo
{
    /**
     * @Inject("vanio_di_extra.tests.foo", required=false)
     */
    public $extendedService;

    /**
     * @Inject
     * @var Foo|null
     */
    public $extendedAutowiredService;
}

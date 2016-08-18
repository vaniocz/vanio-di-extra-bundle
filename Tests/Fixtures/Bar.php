<?php
namespace Vanio\DiExtraBundle\Tests\Fixtures;

use Vanio\DiExtraBundle\DependencyInjection\Metadata\Inject;

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

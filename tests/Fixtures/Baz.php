<?php
namespace Vanio\DiExtraBundle\Tests\Fixtures;

use Vanio\DiExtraBundle\DependencyInjection\Metadata\Inject;

class Baz
{
    /**
     * @Inject
     */
    public $invalid;
}

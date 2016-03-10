<?php
namespace Vanio\VanioDiExtraBundle\Tests\Fixtures;

use Vanio\VanioDiExtraBundle\DependencyInjection\Metadata\Inject;

class Baz
{
    /**
     * @Inject
     */
    public $invalid;
}

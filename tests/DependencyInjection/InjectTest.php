<?php
namespace Vanio\VanioDiExtraBundle\Tests\DependencyInjection;

use Vanio\VanioDiExtraBundle\DependencyInjection\Inject;

class InjectTest extends \PHPUnit_Framework_TestCase
{
    function test_type_can_be_obtained()
    {
        $this->assertNull((new Inject)->type());
        $this->assertSame('type', Inject::byType('type')->type());
    }

    function test_id_can_be_obtained()
    {
        $this->assertNull((new Inject)->id());
        $this->assertSame('id', (new Inject(['value' => 'id']))->id());
    }
}

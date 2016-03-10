<?php
namespace Vanio\VanioDiExtraBundle\Tests\DependencyInjection\Metadata;

use Vanio\VanioDiExtraBundle\DependencyInjection\Metadata\Inject;

class InjectTest extends \PHPUnit_Framework_TestCase
{
    function test_it_is_not_instantiable_using_default_annotation_value()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Inject annotation does not have a default option');
        new Inject(['value' => 'value']);
    }

    function test_id_can_be_obtained()
    {
        $this->assertNull((new Inject)->id());
        $this->assertSame('id', (new Inject(['id' => 'id']))->id());
        $this->assertSame('id', Inject::byId('id')->id());
    }

    function test_type_can_be_obtained()
    {
        $this->assertNull((new Inject)->type());
        $this->assertSame('type', Inject::byType('type')->type());
    }

    function test_parameter_can_be_obtained()
    {
        $this->assertNull((new Inject)->parameter());
        $this->assertSame('parameter', (new Inject(['parameter' => 'parameter']))->parameter());
        $this->assertSame('parameter', Inject::byParameter('parameter')->parameter());
    }

    function test_it_can_be_required()
    {
        $this->assertTrue((new Inject(['id' => 'id', 'required' => true]))->isRequired());
        $this->assertTrue(Inject::byId('id', true)->isRequired());
        $this->assertTrue(Inject::byType('type', true)->isRequired());
    }

    function test_it_does_not_have_to_be_required()
    {
        $this->assertTrue((new Inject(['id' => 'id']))->isRequired());
        $this->assertTrue(Inject::byId('id')->isRequired());
        $this->assertTrue(Inject::byType('type')->isRequired());
    }
}

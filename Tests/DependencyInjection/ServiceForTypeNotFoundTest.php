<?php
namespace Vanio\DiExtraBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Vanio\DiExtraBundle\DependencyInjection\ServiceForTypeNotFound;

class ServiceForTypeNotFoundTest extends TestCase
{
    function test_message_can_be_obtained()
    {
        $message = (new ServiceForTypeNotFound('type'))->getMessage();
        $this->assertSame('You have requested a service for non-resolvable type "type".', $message);
    }

    function test_type_can_be_obtained()
    {
        $this->assertSame('type', (new ServiceForTypeNotFound('type'))->type());
    }
}

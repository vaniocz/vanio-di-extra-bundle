<?php
namespace Vanio\VanioDiExtraBundle\Tests\Fixtures;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Vanio\VanioDiExtraBundle\DependencyInjection\ContainerAwareTrait;
use Vanio\VanioDiExtraBundle\DependencyInjection\Metadata\Inject;

class Foo implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @Inject(id="vanio_di_extra.tests.foo")
     */
    public $service;

    /**
     * @Inject(id="vanio_di_extra.tests.optional_service", required=false)
     */
    public $optionalService;

    /**
     * @Inject
     * @var self
     */
    public $autowiredService;

    /**
     * @Inject
     * @var self|null
     */
    public $optionalAutowiredService;

    /**
     * @Inject(id="vanio_di_extra.tests.foo")
     */
    public $extendedService;

    /**
     * @Inject
     * @var self
     */
    public $extendedAutowiredService;

    /**
     * @Inject(parameter="vanio_di_extra.tests.parameter")
     * @var string
     */
    public $parameter;

    public $none;

    /**
     * @Inject(id="vanio_di_extra.tests.foo")
     */
    private $privateService;

    public function privateService()
    {
        return $this->privateService;
    }
}

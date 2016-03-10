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
     * @var self
     * @Inject
     */
    public $autowiredService;

    /**
     * @var self|null
     * @Inject
     */
    public $optionalAutowiredService;

    /**
     * @Inject(id="vanio_di_extra.tests.foo")
     */
    public $extendedService;

    /**
     * @var self
     * @Inject
     */
    public $extendedAutowiredService;

    /**
     * @var string
     * @Inject(parameter="vanio_di_extra.tests.parameter")
     */
    public $parameter;

    /**
     * @Inject(id="vanio_di_extra.tests.private_service")
     */
    public $privateService;

    /**
     * @var Baz
     * @Inject
     */
    public $autowiredPrivateService;

    /**
     * @var Qux
     * @Inject
     */
    public $autowiredPrivateServiceUsingPublicAlias;

    public $none;

    /**
     * @Inject(id="vanio_di_extra.tests.foo")
     */
    private $privateProperty;

    public function privateProperty()
    {
        return $this->privateProperty;
    }
}

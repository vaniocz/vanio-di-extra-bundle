<?php
namespace Vanio\DiExtraBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerInterface;

trait ContainerAwareTrait
{
    /** @var Container */
    protected $container;

    /** @var Injector */
    private $injector;

    /**
     * @required
     */
    public function setContainer(ContainerInterface $container = null): void
    {
        if (!$container) {
            return;
        }

        $this->container = $container;
        $this->injector = $container->get('vanio_di_extra.dependency_injection.injector');
        $this->injector->initializeProperties($this);
    }

    /**
     * @param string $property
     * @return mixed
     */
    public function __get(string $property)
    {
        $this->injector->injectIntoProperty($this, $property);

        return $this->$property;
    }
}

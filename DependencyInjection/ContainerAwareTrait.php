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
     * Sets a container and initializes properties for injection.
     *
     * @required
     * @param Container|null $container
     * @throws \InvalidArgumentException
     */
    public function setContainer(ContainerInterface $container = null)
    {
        if (!$container instanceof Container) {
            throw new \InvalidArgumentException(sprintf(
                'Container must be an instance of "%s" class. Haven\'t you forgot to reimplement "getContainerBaseClass" inside your AppKernel class as it is described in README?',
                Container::class
            ));
        }

        $this->container = $container;
        $this->injector = $container->get('vanio_di_extra.dependency_injection.injector');
        $this->injector->initializeProperties($this);
    }

    /**
     * Tries to inject service or parameter into the given property.
     *
     * @param string $property
     * @return mixed
     */
    public function __get(string $property)
    {
        $this->injector->injectIntoProperty($this, $property);

        return $this->$property;
    }
}

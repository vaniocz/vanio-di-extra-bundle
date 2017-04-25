<?php
namespace Vanio\DiExtraBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\AutowirePass as BaseAutowirePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * {@inheritDoc}
 * Stores a map of all autowirable types as keys and public service definition or alias IDs
 * into "vanio_di_extra.autowirable_types" container parameter.
 */
class AutowirePass extends BaseAutowirePass
{
    /** @var ContainerBuilder|null */
    protected $container;

    public function process(ContainerBuilder $container)
    {
        $this->container = $container;
        $container->setParameter('vanio_di_extra.autowirable_types', $this->resolveAutowirableTypes());
        parent::process($container);
    }

    /**
     * @return string[]
     */
    private function resolveAutowirableTypes(): array
    {
        $types = $this->resolveAvailableTypes();

        foreach ($types as $type => $id) {
            if (!$this->container->getDefinition($id)->isPublic()) {
                $types[$type] = $this->findPublicAliasId($id);
            }
        }

        return $types;
    }

    /**
     * @return string[]
     */
    private function resolveAvailableTypes(): array
    {
        $container = $this->container;

        $resolveAvailableTypes = function () use ($container) {
            $this->{'container'} = $container;

            if ($this->{'types'} === null) {
                $this->{'populateAvailableTypes'}();
            }

            return $this->{'types'};
        };

        $resolveAvailableTypes = $resolveAvailableTypes->bindTo($this, parent::class);

        return $resolveAvailableTypes();
    }

    /**
     * @param string $serviceId
     * @return string|null
     */
    private function findPublicAliasId(string $serviceId)
    {
        foreach ($this->container->getAliases() as $id => $alias) {
            if ($alias->isPublic() && $serviceId === (string) $alias) {
                return $id;
            }
        }

        return null;
    }
}

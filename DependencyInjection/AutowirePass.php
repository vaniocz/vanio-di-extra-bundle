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

    /** @var string[]|null */
    private $publicAliases;

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
                $types[$type] = $this->publicAliases()[$id] ?? null;
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
     * @return string[]
     */
    private function publicAliases(): array
    {
        if ($this->publicAliases === null) {
            $this->publicAliases = [];

            foreach ($this->container->getAliases() as $id => $alias) {
                if (!$alias->isPublic()) {
                    continue;
                }

                while ($this->container->hasAlias((string) $alias)) {
                    $alias = $this->container->getAlias($alias);
                }

                $this->publicAliases[(string) $alias] = $id;
            }
        }

        return $this->publicAliases;
    }
}

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
    /** @var ContainerBuilder */
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
        $autowirableTypes = [];

        foreach ($this->resolveAvailableTypes() as $type => $id) {
            $autowirableTypes[$type] = $this->resolvePublicId($id);
        }

        foreach ($this->resolveAmbiguousServiceTypes() as $type => $ids) {
            if ($this->container->hasAlias($type)) {
                $autowirableTypes[$type] = $this->resolvePublicId($type);
            }
        }

        return $autowirableTypes;
    }

    /**
     * @return string[]
     */
    private function resolveAvailableTypes(): array
    {
        $container = $this->container;

        $resolveAvailableTypes = function () use ($container) {
            $this->container = $container;

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
    private function resolveAmbiguousServiceTypes(): array
    {
        $container = $this->container;

        $resolveAmbiguousServiceTypes = function () use ($container) {
            $this->container = $container;

            if ($this->{'ambiguousServiceTypes'} === null) {
                $this->{'populateAvailableTypes'}();
            }

            return $this->{'ambiguousServiceTypes'};
        };

        $resolveAmbiguousServiceTypes = $resolveAmbiguousServiceTypes->bindTo($this, parent::class);

        return $resolveAmbiguousServiceTypes();
    }

    /**
     * @param string $id
     * @return string|null
     */
    public function resolvePublicId(string $id)
    {
        if ($this->container->hasAlias($id)) {
            $id = $this->resolveAliasId($id);
        }

        return $this->container->getDefinition($id)->isPublic() ? $id : $this->publicAliases()[$id] ?? null;
    }

    private function resolveAliasId(string $alias): string
    {
        while ($this->container->hasAlias((string) $alias)) {
            $alias = $this->container->getAlias($alias);
        }

        while ($this->container->hasAlias("$alias.inner")) {
            $alias = $this->container->getAlias("$alias.inner");
        }

        return $alias;
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

                $this->publicAliases[$this->resolveAliasId($alias)] = $id;
            }
        }

        return $this->publicAliases;
    }
}

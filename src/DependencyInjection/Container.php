<?php
namespace Vanio\VanioDiExtraBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Container as BaseContainer;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;

class Container extends BaseContainer
{
    /**
     * Gets a service by type.
     *
     * @param string $type The service type
     * @param int $invalidBehavior The behavior when the service does not exist
     *
     * @return object|null The associated service
     *
     * @throws ServiceCircularReferenceException When a circular reference is detected
     * @throws ServiceForTypeNotFound When the service is not found
     * @throws \Exception If an exception has been thrown when the service has been resolved
     */
    public function getByType(string $type, $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE)
    {
        if (!$id = $this->getParameter('vanio_di_extra_autowirable_types')[$type] ?? null) {
            if ($invalidBehavior === self::EXCEPTION_ON_INVALID_REFERENCE) {
                throw new ServiceForTypeNotFound($type);
            }

            return null;
        }

        return $this->get($id, $invalidBehavior);
    }
}

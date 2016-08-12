<?php
namespace Vanio\DiExtraBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

class ServiceForTypeNotFound extends InvalidArgumentException
{
    /** @var string */
    private $type;

    public function __construct(string $type, int $code = 0, \Exception $previous = null)
    {
        $this->type = $type;
        $message = sprintf('You have requested a service for non-resolvable type "%s".', $type);
        parent::__construct($message, $code, $previous);
    }

    public function type(): string
    {
        return $this->type;
    }
}

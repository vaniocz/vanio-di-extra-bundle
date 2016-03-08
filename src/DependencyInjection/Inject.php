<?php
namespace Vanio\VanioDiExtraBundle\DependencyInjection;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Inject
{
    /** @var string|null */
    private $id;

    /** @var string|null */
    private $type;

    public function __construct(array $options = [])
    {
        $this->id = isset($options['value']) ? (string) $options['value'] : null;
    }

    public static function byType(string $type): self
    {
        $self = new self;
        $self->type = $type;

        return $self;
    }

    /**
     * @return string|null
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function type()
    {
        return $this->type;
    }
}

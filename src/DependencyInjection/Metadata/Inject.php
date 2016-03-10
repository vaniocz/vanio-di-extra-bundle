<?php
namespace Vanio\VanioDiExtraBundle\DependencyInjection\Metadata;

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

    /** @var string|null */
    private $parameter;

    /** @var bool */
    private $required;

    /**
     * @param array $options An array of options, available keys are "id", "parameter" and "required"
     */
    public function __construct(array $options = [])
    {
        $this->id = isset($options['id']) ? (string) $options['id'] : null;
        $this->parameter = isset($options['parameter']) ? (string) $options['parameter'] : null;
        $this->required = isset($options['required']) ? (bool) $options['required'] : true;

        if (isset($options['value'])) {
           throw new \InvalidArgumentException('Inject annotation does not have a default option, use "id", "parameter", "required" option or annotate the property using @var annotation.');
        }
    }

    public static function byId(string $id, bool $required = true): self
    {
        $self = new self;
        $self->id = $id;
        $self->required = $required;

        return $self;
    }

    public static function byType(string $type, bool $required = true): self
    {
        $self = new self;
        $self->type = $type;
        $self->required = $required;

        return $self;
    }

    public static function byParameter(string $parameter, bool $required = true): self
    {
        $self = new self;
        $self->parameter = $parameter;
        $self->required = $required;

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

    /**
     * @return string|null
     */
    public function parameter()
    {
        return $this->parameter;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }
}

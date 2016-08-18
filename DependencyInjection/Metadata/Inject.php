<?php
namespace Vanio\DiExtraBundle\DependencyInjection\Metadata;

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
     * Annotation constructor
     *
     * @param array $options Available keys are (string) "value" (the default option) and (bool) "required"
     *                       Value represents an ID or a parameter expression when it contains a percent sign
     */
    public function __construct(array $options = [])
    {
        if (isset($options['value'])) {
            if (strpos($options['value'], '%') === false) {
                $this->id = (string) $options['value'];
            } else {
                $this->parameter = (string) $options['value'];
            }
        }

        $this->required = isset($options['required']) ? (bool) $options['required'] : true;
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

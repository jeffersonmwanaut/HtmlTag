<?php
namespace HtmlTag\Form\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Input implements FormControlAttributeInterface
{
    use AutoSerializableAttribute;

    public function __construct(
        protected string $type = 'text',
        protected ?string $label = null,
        protected ?string $placeholder = null,
        protected ?bool $required = null,
        protected ?string $name = null,
        protected ?string $id = null,
        protected ?string $class = null,
        protected ?string $style = null,
        protected ?string $pattern = null,
        protected ?int $min = null,
        protected ?int $max = null,
        protected ?int $maxlength = null,
        protected ?bool $readonly = null,
        protected ?bool $disabled = null,
        protected ?string $wrapperClass = null
    ) {}

    public function attr(string $attribute, string $value): self
    {
        if (property_exists($this, $attribute)) {
            $this->$attribute = $value;
        }
        return $this;
    }
}
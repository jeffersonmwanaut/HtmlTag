<?php
namespace HtmlTag\Form\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Button implements FormControlAttributeInterface
{
    use AutoSerializableAttribute;

    public function __construct(
        protected string $type = 'button',
        protected ?string $value = null,
        protected ?string $name = null,
        protected ?string $id = null,
        protected ?string $class = null,
        protected ?string $style = null,
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
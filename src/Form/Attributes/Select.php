<?php
namespace HtmlTag\Form\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Select implements FormControlAttributeInterface
{
    use AutoSerializableAttribute;

    public function __construct(
        protected ?string $label = null,
        protected bool $multiple = false,
        protected ?string $class = null,
        protected ?string $style = null,
        protected ?bool $required = null,
        protected ?string $wrapperClass = null
    ) {}
}

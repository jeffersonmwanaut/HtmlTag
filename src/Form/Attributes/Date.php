<?php
namespace HtmlTag\Form\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Date implements FormControlAttributeInterface
{
    use AutoSerializableAttribute;

    public function __construct(
        protected ?string $label = null,
        protected ?string $min = null,
        protected ?string $max = null,
        protected ?string $class = null,
        protected ?string $style = null,
        protected ?bool $required = null,
        protected ?string $wrapperClass = null
    ) {}
}

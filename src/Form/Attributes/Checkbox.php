<?php
namespace HtmlTag\Form\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Checkbox implements FormControlAttributeInterface
{
    use AutoSerializableAttribute;

    public function __construct(
        protected ?string $label = null,
        protected ?bool $checked = null,
        protected ?string $class = null,
        protected ?string $style = null,
        protected ?string $wrapperClass = null
    ) {}
}

<?php
namespace HtmlTag\Form\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Label implements FormControlAttributeInterface
{
    use AutoSerializableAttribute;

    public function __construct(
        protected ?string $text = null,
        protected ?string $for = null,
        protected ?string $class = null,
        protected ?string $style = null
    ) {}
}

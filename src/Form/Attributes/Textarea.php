<?php
namespace HtmlTag\Form\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Textarea implements FormControlAttributeInterface
{
    use AutoSerializableAttribute;

    public function __construct(
        protected ?string $label = null,
        protected ?int $rows = 4,
        protected ?int $cols = null,
        protected ?string $placeholder = null,
        protected ?bool $required = null,
        protected ?string $class = null,
        protected ?string $style = null,
        protected ?string $wrapperClass = null
    ) {}
}

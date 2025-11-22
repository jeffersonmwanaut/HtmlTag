<?php
namespace HtmlTag\Form\Attributes;

use ReflectionClass;

trait AutoSerializableAttribute
{
    public function toArray(): array
    {
        $ref = new ReflectionClass($this);
        $ctor = $ref->getConstructor();

        if (!$ctor) return [];

        $data = [];
        foreach ($ctor->getParameters() as $param) {
            $name = $param->getName();
            $data[$name] = $this->{$name};
        }

        return $data;
    }
}

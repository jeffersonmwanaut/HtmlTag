<?php
namespace HtmlTag\Form;

use HtmlTag\HtmlTag;
use HtmlTag\Config;

class FormBuilder
{
    private Config $config;

    public function __construct(Config $config) 
    {
        $this->config = $config;
    }

    public function create(object $entity, string $action = '#', string $method = 'post'): Form
    {
        $form = new Form($action, $method, config: $this->config);

        // -------- build based on entity fields --------
        // Use reflection to get the properties of the entity
        $reflectionClass = new \ReflectionClass($entity);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);

            $propertyName = $property->getName();
            $propertyType = $property->getType();
            $label = new Label(for: $propertyName, text: $propertyName); // Create a label from the property name
            
            // determine input type
            $inputType = $this->inferInputType($propertyType);

            // Safely get property value (only if initialized)
            $value = $property->isInitialized($entity)
                ? $property->getValue($entity)
                : null;

            // Use textarea if configured
            if ($this->isTextareaField($propertyName)) {
                $textarea = new Textarea(name: $propertyName, id: $propertyName);
                $textarea->appendText((string)$value);
                $form->addControl(control: $textarea, label: $label);
                continue;
            }

            if ($inputType === 'select') {
                $select = new Select(name: $propertyName, id: $propertyName);
                $options = $this->getSelectOptions($entity, $propertyName, $value);
                foreach ($options as $key => $text) {
                    $select->appendChild(new Option(value: $key, text: $text));
                }
                $form->addControl(control: $select, label: $label);
                continue;
            }

            $input = new Input(type: $inputType, name: $propertyName, id: $propertyName);

            if ($inputType === 'checkbox' && $value === true) {
                $input->attr('checked', true);
            }

            if ($value !== null && $inputType !== 'checkbox') {
                $input->attr('value', $value);
            }

            $form->addControl($input, $label);
        }

        return $form;
    }

    private function inferInputType(?\ReflectionNamedType $type): string
    {
        if (!$type) {
            return 'text';
        }

        return match ($type->getName()) {
            'int', 'float' => 'number',
            'bool'         => 'checkbox',
            'array'        => 'select',
            default        => 'text'
        };
    }

    private function getSelectOptions(object $entity, string $propertyName, mixed $value): array
    {
        $getter = 'get' . ucfirst($propertyName);

        if (method_exists($entity, $getter)) {
            return $entity->$getter();
        }

        return is_array($value) ? $value : [];
    }

    public function isTextareaField(string $fieldName): bool
    {
        $hints = $this->config->get('form.textareaHints') ?? [];
        return in_array(strtolower($fieldName), array_map('strtolower', $hints));
    }

}
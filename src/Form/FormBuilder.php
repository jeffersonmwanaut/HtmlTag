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
        $styleConfig = $this->config->get('form')['style'] ?? null;
        $form = new Form($action, $method, styleConfig: $styleConfig);

        // Use reflection to get the properties of the entity
        $reflectionClass = new \ReflectionClass($entity);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);

            $propertyName = $property->getName();
            $propertyType = $property->getType();
            
            // Create input based on property type
            $inputType = 'text'; // Default input type
            $label = new Label(for: $propertyName, text: $propertyName); // Create a label from the property name

            if ($propertyType) {
                $typeName = $propertyType->getName();
                switch ($typeName) {
                    case 'int':
                    case 'float':
                        $inputType = 'number';
                        break;
                    case 'bool':
                        $inputType = 'checkbox';
                        break;
                    case 'array':
                        $inputType = 'select';
                        break;
                }
            }

            // Safely get property value (only if initialized)
            $value = null;
            if ($property->isInitialized($entity)) {
                $value = $property->getValue($entity);
            }

            // ✅ Use textarea if configured
            if ($this->isTextareaField($propertyName)) {
                $textarea = new Textarea(name: $propertyName, id: $propertyName);
                if ($value !== null) {
                    $textarea->appendText($value);
                }
                $form->addControl(control: $textarea, label: $label);
            }
            elseif ($inputType === 'select') {
                $select = new Select(name: $propertyName, id: $propertyName);
                $getter = 'get' . ucfirst($propertyName);
                $options = method_exists($entity, $getter) ? $entity->$getter() : (is_array($value) ? $value : []);
                foreach ($options as $key => $text) {
                    $optionValue = is_string($key) ? $key : $text;
                    $select->appendChild(new Option(value: $optionValue, text: $text));
                }
                $form->addControl(control: $select, label: $label);
            }
            elseif ($inputType === 'checkbox') {
                $input = new Input(type: 'checkbox', name: $propertyName, id: $propertyName);
                if ($value === true) {
                    $input->attr('checked', true);
                }
                $form->addControl(control: $input, label: $label);
            }
            else {
                $input = new Input(type: $inputType, name: $propertyName, id: $propertyName);
                if ($value !== null) {
                    $input->attr('value', $value);
                }
                $form->addControl(control: $input, label: $label);
            }
        }

        return $form;
    }

    public function isTextareaField(string $fieldName): bool
    {
        $normalized = strtolower($fieldName);
        return in_array($normalized, array_map('strtolower', $this->config->get('form')['textareaHints']), true);
    }

}
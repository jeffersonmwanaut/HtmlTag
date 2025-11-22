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

            // Safely get property value (only if initialized)
            $value = $property->isInitialized($entity)
                ? $property->getValue($entity)
                : null;

            $attributes   = $property->getAttributes();

            // -----------------------------------------------------
            // ATTRIBUTE-BASED MODE
            // -----------------------------------------------------
            foreach ($attributes as $attr) {
                $attrInstance = $attr->newInstance();

                $labelPosition = $attrInstance->toArray()['labelPosition'] ?? 'before';
                $wrapper = $attrInstance->toArray()['wrapper'] ?? 'div';
                $wrapperClass = $attrInstance->toArray()['wrapperClass'] ?? null;

                // BUTTON ATTRIBUTE → create <button>
                if ($attrInstance instanceof \HtmlTag\Form\Attributes\Button) {
                    $button = new Button(type: $attrInstance->toArray()['type'] ?? 'button');

                    // Apply attributes dynamically
                    $this->applyAttributeToControl($attrInstance, $button);

                    // Buttons do NOT have labels
                    $form->appendControl(control: $button, wrapper: $wrapper, wrapperClass: $wrapperClass);
                    continue 2; // skip default generation
                }

                // INPUT ATTRIBUTE → create <input>
                if ($attrInstance instanceof \HtmlTag\Form\Attributes\Input) {
                    $input = new Input(
                        type: $attrInstance->toArray()['type'] ?? 'text',
                        name: $propertyName,
                        id: $propertyName
                    );

                    $this->applyAttributeToControl($attrInstance, $input);
                    if ($value !== null) {
                        $input->attr('value', $value);
                    }

                    $labelText = $attrInstance->toArray()['label'] ?? $propertyName;
                    $label = new Label(for: $propertyName, text: $labelText);

                    $form->appendControl(control: $input, label: $label, labelPosition: $labelPosition, wrapper: $wrapper, wrapperClass: $wrapperClass);
                    continue 2;
                }

                // TEXTAREA ATTRIBUTE → create <textarea>
                if ($attrInstance instanceof \HtmlTag\Form\Attributes\Textarea) {
                    $textarea = new Textarea(
                        name: $propertyName,
                        id:   $propertyName
                    );

                    $this->applyAttributeToControl($attrInstance, $textarea);
                    if ($value !== null) {
                        $textarea->appendText((string)$value);
                    }

                    $labelText = $attrInstance->toArray()['label'] ?? $propertyName;
                    $label = new Label(for: $propertyName, text: $labelText);

                    $form->appendControl(control: $textarea, label: $label, labelPosition: $labelPosition, wrapper: $wrapper, wrapperClass: $wrapperClass);
                    continue 2;
                }

                // SELECT ATTRIBUTE → create <select>
                if ($attrInstance instanceof \HtmlTag\Form\Attributes\Select) {
                    $select = new Select(
                        name: $propertyName,
                        id:   $propertyName
                    );

                    $this->applyAttributeToControl($attrInstance, $select);

                    foreach ($this->getSelectOptions($entity, $propertyName, $value) as $k => $v) {
                        $option = new Option(value: $k, text: $v);
                        if ($value == $k) {
                            $option->attr('selected', 'selected');
                        }
                        $select->appendChild($option);
                    }

                    $labelText = $attrInstance->toArray()['label'] ?? $propertyName;
                    $label = new Label(for: $propertyName, text: $labelText);

                    $form->appendControl(control: $select, label: $label, labelPosition: $labelPosition, wrapper: $wrapper, wrapperClass: $wrapperClass);
                    continue 2;
                }

                // CHECKBOX ATTRIBUTE
                if ($attrInstance instanceof \HtmlTag\Form\Attributes\Checkbox) {
                    $input = new Input(type: 'checkbox', name: $propertyName, id: $propertyName);

                    $this->applyAttributeToControl($attrInstance, $input);
                    if ($value === true) {
                        $input->attr('checked', true);
                    }

                    $labelText = $attrInstance->toArray()['label'] ?? $propertyName;
                    $label = new Label(for: $propertyName, text: $labelText);

                    $form->appendControl(control: $input, label: $label, labelPosition: $labelPosition, wrapper: $wrapper, wrapperClass: $wrapperClass);
                    continue 2;
                }
            }
            
            // -----------------------------------------------------
            // NO ATTRIBUTE → DEFAULT GENERATION
            // -----------------------------------------------------
            // Use textarea if configured
            if ($this->isTextareaField($propertyName)) {
                $textarea = new Textarea(name: $propertyName, id: $propertyName);
                $textarea->appendText((string)$value);
                $form->addControl(control: $textarea, label: $label);
                continue;
            }

            // determine input type
            $inputType = $this->inferInputType($propertyType);

            if ($inputType === 'select') {
                $select = new Select(name: $propertyName, id: $propertyName);
                $options = $this->getSelectOptions($entity, $propertyName, $value);
                foreach ($options as $key => $text) {
                    $select->appendChild(new Option(value: $key, text: $text));
                }
                $form->appendControl(control: $select, label: $label);
                continue;
            }

            $input = new Input(type: $inputType, name: $propertyName, id: $propertyName);

            if ($inputType === 'checkbox' && $value === true) {
                $input->attr('checked', true);
            }

            if ($value !== null && $inputType !== 'checkbox') {
                $input->attr('value', $value);
            }

            $form->appendControl(control: $input, label: $label);
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

    private function applyAttributeToControl(object $attribute, HtmlTag $control): void
    {
        if (!method_exists($attribute, 'toArray')) {
            return;
        }

        $attrs = $attribute->toArray();

        foreach ($attrs as $name => $value) {
            if ($name === 'label') {
                continue; // ignore label, not an HTML attribute
            }

            // Skip explicit false booleans — they must NOT appear
            if ($value === false) {
                continue;
            }

            // Boolean true → render attribute without a value
            if ($value === true) {
                $control->attr($name, $name);
                continue;
            }

            // Skip nulls
            if ($value === null) {
                continue;
            }

            // Normal attributes
            $control->attr($name, $value);
        }
    }
}
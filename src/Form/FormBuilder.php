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
            // -----------------------------------------------------
            // Only include properties with attribute #[FormControl]
            // -----------------------------------------------------
            if (empty($property->getAttributes(\HtmlTag\Form\Attributes\FormControl::class))) {
                continue;
            }

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

                // Skip the marker attribute itself — it's only used to include the property
                if ($attrInstance instanceof \HtmlTag\Form\Attributes\FormControl) {
                    continue;
                }

                // Being defensive: only call toArray() if attribute provides it
                $data = [];
                if (method_exists($attrInstance, 'toArray')) {
                    $data = $attrInstance->toArray();
                }

                $labelPosition = $data['labelPosition'] ?? 'before';
                $wrapper       = $data['wrapper'] ?? 'div';
                $wrapperClass  = $data['wrapperClass'] ?? null;

                //$labelPosition = $attrInstance->toArray()['labelPosition'] ?? 'before';
                //$wrapper = $attrInstance->toArray()['wrapper'] ?? 'div';
                //$wrapperClass = $attrInstance->toArray()['wrapperClass'] ?? null;

                // BUTTON ATTRIBUTE → create <button>
                if ($attrInstance instanceof \HtmlTag\Form\Attributes\Button) {
                    $button = new Button(type: $data['type'] ?? 'button');

                    // Apply attributes dynamically
                    $this->applyAttributeToControl($attrInstance, $button);

                    // Buttons do NOT have labels
                    $form->appendControl(control: $button, wrapper: $wrapper, wrapperClass: $wrapperClass);
                    continue 2; // skip default generation
                }

                // INPUT ATTRIBUTE → create <input>
                if ($attrInstance instanceof \HtmlTag\Form\Attributes\Input) {
                    $input = new Input(
                        type: $data['type'] ?? 'text',
                        name: $propertyName,
                        id: $propertyName
                    );

                    $this->applyAttributeToControl($attrInstance, $input);
                    if ($value !== null) {
                        $input->attr('value', $value);
                    }

                    $labelText = $data['label'] ?? $propertyName;
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

                    $labelText = $data['label'] ?? $propertyName;
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

                    $labelText = $data['label'] ?? $propertyName;
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

                    $labelText = $data['label'] ?? $propertyName;
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
        /*$getter = 'get' . ucfirst($propertyName);

        if (method_exists($entity, $getter)) {
            return $entity->$getter();
        }

        return is_array($value) ? $value : [];*/

        $getter = 'get' . ucfirst($propertyName);

        // CASE 1 — class defines a getter returning array
        if (method_exists($entity, $getter)) {
            $returned = $entity->$getter();
            if (is_array($returned)) {
                return $returned;
            }
        }

        // CASE 2 — property contains an array directly
        if (is_array($value)) {
            return $value;
        }

        // CASE 3 — nested entity with SelectOption annotations
        if (is_object($value)) {
            return $this->extractOptionsFromNestedObject($value);
        }

        return [];
    }

    private function extractOptionsFromNestedObject(object $obj): array
    {
        $refClass = new \ReflectionClass($obj);
        $properties = $refClass->getProperties();

        $valueField = null;
        $textField = null;

        foreach ($properties as $prop) {
            foreach ($prop->getAttributes() as $attr) {
                $instance = $attr->newInstance();

                if ($instance instanceof \HtmlTag\Form\Attributes\SelectOptionValue) {
                    $valueField = $prop;
                }

                if ($instance instanceof \HtmlTag\Form\Attributes\SelectOptionText) {
                    $textField = $prop;
                }
            }
        }

        if (!$valueField || !$textField) {
            return [];
        }

        $valueField->setAccessible(true);
        $textField->setAccessible(true);

        return [
            $valueField->getValue($obj) => $textField->getValue($obj)
        ];
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
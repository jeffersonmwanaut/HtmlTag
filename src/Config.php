<?php
namespace HtmlTag;

class Config
{
    private array $config = [];

    public function __construct(string $configPath)
    {
        if (!file_exists($configPath)) {
            throw new \RuntimeException("Form config file not found: $configPath");
        }

        $config = json_decode(file_get_contents($configPath), true);

        if (!is_array($config)) {
            throw new \RuntimeException("Invalid JSON format in $configPath");
        }

        $this->config = $config;
    }

    /**
     * Supports both simple keys ("form") and dotted keys ("form.style")
     */
    public function get(string $key, $default = null)
    {
        // No dot = direct key lookup (backwards compatible)
        if (!str_contains($key, '.')) {
            return $this->config[$key] ?? $default;
        }

        // Dot notation: walk through nested arrays
        $parts = explode('.', $key);
        $value = $this->config;

        foreach ($parts as $part) {
            if (!is_array($value) || !array_key_exists($part, $value)) {
                return $default;
            }
            $value = $value[$part];
        }

        return $value;
    }
}
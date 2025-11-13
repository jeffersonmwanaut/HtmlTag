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

    public function get(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }
}
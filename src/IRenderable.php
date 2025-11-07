<?php
namespace HtmlTag;

/**
 * Interface for renderable content
 */
interface IRenderable
{
    public function render(): string;
}
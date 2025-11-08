# HtmlTag
Lightweight HTML builder designed to programmatically generate HTML tags in PHP without manually concatenating strings.

## Table of contents

- [Purpose of HtmlTag](#purpose-of-htmltag)
- [Basic Structure](#basic-structure)
- [Setting Attributes](#setting-attributes)
- [Adding Text or Inner HTML](#adding-text-or-inner-html)
- [Nesting Tags](#nesting-tags)
- [Self-closing Tags](#self-closing-tags)
- [Practical Examples](#practical-examples)
- [Rendering Options](#rendering-options)
- [Integration in your project](#integration-in-your-project)
- [Summary of Common Methods](#summary-of-common-methods)
- [Creators](#creators)

## Purpose of `HtmlTag`

The `HtmlTag` class helps you create HTML elements using object-oriented syntax, e.g.:

```php
use HtmlTag\HtmlTag;

echo (new HtmlTag('a'))
    ->attr('href', 'https://zanabler.com')
    ->appendText('Visit Zana')
    ->render();
```

This produces:

```html
<a href="https://zanabler.com">Visit Zanabler</a>
```

## Basic Structure

Instantiating a Tag.

```php
$tag = new HtmlTag('div');
```

You can also chain everything directly:

```php
echo (new HtmlTag('p'))->appendText('Hello World!')->render();
```

## Setting Attributes

Use `.attr($name, $value)` to add or update an attribute:

```php
echo (new HtmlTag('input'))
    ->attr('type', 'text')
    ->attr('name', 'username')
    ->attr('placeholder', 'Enter your name')
    ->render();
```

Output:

```html
<input type="text" name="username" placeholder="Enter your name">
```

## Adding Text or Inner HTML

- `appendText($string)`: sets inner text.
- `appendHtml($html)`: sets raw inner HTML.

Example:

```php
echo (new HtmlTag('div'))
    ->attr('class', 'container')
    ->appendHtml('<p>Welcome <strong>Jefferson</strong></p>')
    ->render();
```

Output:

```html
<div class="container"><p>Welcome <strong>Jefferson</strong></p></div>
```

## Nesting Tags

You can append child tags using .appendChild($childTag):

```php
$ul = new HtmlTag('ul');

$ul->appendChild((new HtmlTag('li'))->text('Home'))
   ->appendChild((new HtmlTag('li'))->text('About'))
   ->appendChild((new HtmlTag('li'))->text('Contact'));

echo $ul->render();
```

Output:

```html
<ul>
    <li>Home</li>
    <li>About</li>
    <li>Contact</li>
</ul>
```

## Self-closing Tags

Tags like `<img>`, `<input>`, `<br>`, etc. automatically close properly:

```php
echo (new HtmlTag('img'))
    ->attr('src', 'logo.png')
    ->attr('alt', 'Company Logo')
    ->render();
```

Output:

```html
<img src="logo.png" alt="Company Logo">
```

## Practical Examples

### Example: Form Input Group

```php
$formGroup = (new HtmlTag('div'))
    ->attr('class', 'form-group')
    ->appendChild((new HtmlTag('label'))->attr('for', 'email')->text('Email:'))
    ->appendChild((new HtmlTag('input'))
                        ->attr('type', 'email')
                        ->attr('id', 'email')
                        ->attr('name', 'email')
                        ->attr('class', 'form-control'));

echo $formGroup->render();
```

Output:

```html
<div class="form-group">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" class="form-control">
</div>
```

## Rendering Options

Use `.render()` to get the HTML string.
If you echo the object directly, it automatically calls `__toString()`, meaning:

```php
echo new HtmlTag('br');
```

directly renders `<br>`.

## Integration in your project

You can use this library as helper anywhere in your controllers or view templates to dynamically generate HTML, especially useful for forms, tables, or components built from PHP arrays.

For instance:

```php
foreach ($users as $user) {
    echo (new HtmlTag('tr'))
        ->appendChild((new HtmlTag('td'))->text($user['name']))
        ->appendChild((new HtmlTag('td'))->text($user['email']))
        ->render();
}
```

## Summary of Common Methods

| Methods                   | Description                                       |
| ------------------------- | ------------------------------------------------- |
| `__construct($tagName)`   | Create a new HTML tag                             |
| `attr($name, $value)`     | Add or set an attribute                           |
| `attrs($array)`           | Add multiple attributes at once (if supported)    |
| `appendText($text)`       | Set inner text                                    |
| `appendHtml($html)`       | Set raw inner HTML                                |
| `appendChild($childTag)`  | Append another HTMLTag as a child                 |
| `render()`                | Render the tag as a string                        |

## Creators

**Jefferson Mwanaut**

- <https://github.com/jeffersonmwanaut>
- <https://www.linkedin.com/in/jeffersonmwanaut>
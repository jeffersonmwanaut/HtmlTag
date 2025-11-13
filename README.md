# HtmlTag
Lightweight HTML builder designed to programmatically generate HTML tags in PHP without manually concatenating strings.

## Table of contents

- [Purpose of HtmlTag](#purpose-of-htmltag)
- [Directory Structure](#directory-structure)
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
    ->appendText('Visit Zanabler')
    ->render();
```

This produces:

```html
<a href="https://zanabler.com">Visit Zanabler</a>
```

## Directory Structure

```
HtmlTag/
 ├── js/
 │   └── form-enhancer.js
 ├── src/
 │   ├── Form/
 │   ├── List/
 │   ├── Table/
 │   ├── Article.php
 │   ├── Aside.php
 │   └── ...
 ├── vendor/
 ├── config.json
 └── README.md
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

$ul->appendChild((new HtmlTag('li'))->appendText('Home'))
   ->appendChild((new HtmlTag('li'))->appendText('About'))
   ->appendChild((new HtmlTag('li'))->appendText('Contact'));

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
    ->appendChild((new HtmlTag('label'))->attr('for', 'email')->appendText('Email:'))
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

### Example: Building a form

```php
use HtmlTag\Form\Form;
use HtmlTag\Form\Input;
use HtmlTag\Form\Label;
use HtmlTag\Form\Button;

$form = (new Form())
    ->attr('action', '/submit')
    ->attr('method', 'post')
    ->appendChild((new Label('Name:'))->attr('for', 'name'))
    ->appendChild((new Input('text', 'name'))->attr('id', 'name'))
    ->appendChild((new Button('Submit'))->attr('type', 'submit'));

echo $form; // Outputs the complete HTML
```

Output:

```html
<form action="/submit" method="post">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name">
    <button type="submit">Submit</button>
</form>
```

### Example: Working with `<select>` and `<option>`

```php
use HtmlTag\Form\Select;
use HtmlTag\Form\Option;

$select = new Select('country');
$select
    ->appendChild(new Option('CD', 'Congo'))
    ->appendChild(new Option('KE', 'Kenya')->attr('selected', true));

echo $select;
```

Output:

```html
<select name="country">
    <option value="CD">Congo</option>
    <option value="KE" selected>Kenya</option>
</select>
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
        ->appendChild((new HtmlTag('td'))->appenText($user['name']))
        ->appendChild((new HtmlTag('td'))->appendText($user['email']))
        ->render();
}
```

## Form
### Form Builder

`HtmlTag\form\FormBuilder` uses reflection to automatically generate HTML forms based on your entity classes, while remaining fully customizable.

It offers the following features:

- Automatic form generation from entity classes using Reflection.
- Type-aware controls (text, number, checkbox, select, textarea, etc.).
- Smart textarea detection using configurable text hints.
- Configurable styling (Bootstrap, Tailwind, or custom CSS classes).
- Dependency injection support for configuration and services.
- Separation of markup and logic — produces HTML elements via HtmlTag abstraction.
- Customizable input labels with automatic spacing for camelCase or PascalCase names.

#### Example Usage

Entity

```php
class Person
{
    public string $firstName;
    public string $lastName;
    public string $bio;
    public bool $isEmployee;
    public array $skills = ['PHP', 'C#', 'JavaScript'];
}
```

Generate the Form

```php
use HtmlTag\Form\FormBuilder;

$person = new Person();
$form = FormBuilder::create($person, '/submit', 'post');
echo $form;
```

Generates HTML automatically:

```html
<form action="/submit" method="post" enctype="multipart/form-data">
  <div>
    <label for="firstName">First Name</label>
    <input type="text" name="firstName" value="">
  </div>
  <div>
    <label for="bio">Bio</label>
    <textarea name="bio"></textarea>
  </div>
  <div>
    <label for="isEmployee">Is Employee</label>
    <input type="checkbox" name="isEmployee">
  </div>
  <div>
    <label for="skills">Skills</label>
    <select name="skills">
      <option>PHP</option>
      <option>C#</option>
      <option>JavaScript</option>
    </select>
  </div>
</form>
```

### Configuration

You can define your form behavior through the `config.json` file located outside the `src/` folder.

Example: `config.json`

```json
{
    "form":
    {
        "style": {
            "type": "framework", 
            "name": "bootstrap" 
        }
    }
}
```

You can change `"type": "framework"` to `"custom"` if you want to use your own CSS class, e.g.:

```json
"style": { "type": "custom", "name": "my-form-styles" }
```

**Loading via Dependency Injection**

```php
use HtmlTag\Config;

$configPath = __DIR__ . '/config.json';
$config = new Config($configPath);
$formBuilder = new FormBuilder($config);

$form = $formBuilder->create($person);
```

### Styling Support

You can style forms using popular frameworks or custom classes.
`Form` and `FormBuilder` read the preferred style from the config file or can be changed at runtime.

**Supported Styles**

| Style Option | Description                                                   |
| ------------ | ------------------------------------------------------------- |
| `bootstrap`  | Adds Bootstrap 5 classes (`form-group`, `form-control`, etc.) |
| `tailwind`   | Adds TailwindCSS utility classes                              |
| `custom`     | Applies developer-defined classes                             |
| `none`       | Outputs raw HTML without styling                              |

Example configuration:

```json
{
    "form":
    {
        "style": {
            "type": "framework", 
            "name": "bootstrap" 
        }
    }
}
```

Add this at the bottom of your page or in your layout:

```html
<script src="js/form-enhancer.js"></script>
```

It will automatically detect any form with `data-enhance-style`.

**Example Rendered Output (Bootstrap mode)**

```html
<form action="/submit.php" method="post" enctype="multipart/form-data" data-enhance-style="bootstrap">
  <div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input name="name" type="text" class="form-control">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
```

### Automatic Label Formatting

Property names like `isEmployee`, `firstName`, or `last_name` are automatically converted to:

- Is Employee
- First Name
- Last Name

The builder supports both camelCase and snake_case formats.


## Summary of Common Methods

| Methods                   | Description                                                               |
| ------------------------- | ------------------------------------------------------------------------- |
| `__construct($tagName)`   | Create a new HTML tag                                                     |
| `attr($name, $value)`     | Add or set an attribute                                                   |
| `attrs($array)`           | Add multiple attributes at once (if supported)                            |
| `append($content)`        | Universal append alias — smartly handles text, HTML, or HtmlTag children  |
| `appendText($text)`       | Set inner text                                                            |
| `appendHtml($html)`       | Set raw inner HTML                                                        |
| `appendChild($childTag)`  | Append another HTMLTag as a child                                         |
| `render()`                | Render the tag as a string                                                |

## Creators

**Jefferson Mwanaut**

- <https://github.com/jeffersonmwanaut>
- <https://www.linkedin.com/in/jeffersonmwanaut>
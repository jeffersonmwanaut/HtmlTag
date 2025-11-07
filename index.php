<?php
require __DIR__ . '/vendor/autoload.php';

use HtmlTag\HtmlTag;
use HtmlTag\Article;
use HtmlTag\Form\Select;

$div = (new HtmlTag('div'))
    ->appendText('Welcome ')
    ->appendChild((new HtmlTag('strong'))->appendText('Jefferson'))
    ->appendText('!');
echo $div;

$article = new Article();
$article->attr('class', 'post')
        ->appendHtml('<h2>Article Title</h2><p>Article content goes here.</p>');
echo $article;

$select = (new Select('country'))
    ->addOption('cd', 'Congo', true)
    ->addOption('us', 'United States')
    ->addOption('fr', 'France');
echo $select;
?>

<br>
<label for="browser">Choose your browser from the list:</label>
<input list="browsers" name="browser" id="browser">
<datalist id="browsers">
 <option value="Edge">
 <option value="Firefox">
 <option value="Chrome">
 <option value="Opera">
 <option value="Safari">
</datalist>

<br>
<label for="colors">Pick a color (preferably a red tone):</label>
<input type="color" list="redColors" id="colors">
<datalist id="redColors">
 <option value="#800000">
 <option value="#8B0000">
 <option value="#A52A2A">
 <option value="#DC143C">
</datalist>
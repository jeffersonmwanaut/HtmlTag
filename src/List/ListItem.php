<?php
namespace HtmlTag\List;

use HtmlTag\HtmlTag;

class ListItem extends HtmlTag {
    public function __construct() {
        parent::__construct('li'); // Set the tag to 'li'
    }
}
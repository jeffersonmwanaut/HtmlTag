<?php
namespace HtmlTag\List;

use HtmlTag\HtmlTag;

class UnorderedList extends HtmlTag {
    public function __construct() {
        parent::__construct('ul');
    }
}
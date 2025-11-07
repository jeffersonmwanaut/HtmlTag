<?php
namespace HtmlTag\List;

use HtmlTag\HtmlTag;

class OrderedList extends HtmlTag {
    public function __construct() {
        parent::__construct('ol');
    }
}
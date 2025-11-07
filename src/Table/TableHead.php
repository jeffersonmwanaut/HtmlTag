<?php
namespace HtmlTag\Table;

use HtmlTag\HtmlTag;

class TableHead extends HtmlTag
{
    public function __construct() {
        parent::__construct('thead');
    }
}
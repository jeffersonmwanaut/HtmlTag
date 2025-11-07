<?php
namespace HtmlTag\Table;

use HtmlTag\HtmlTag;

class TableHeaderCell extends HtmlTag
{
    public function __construct() {
        parent::__construct('th');
    }
}
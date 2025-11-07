<?php
namespace HtmlTag\Table;

use HtmlTag\HtmlTag;

class TableDataCell extends HtmlTag
{
    public function __construct() {
        parent::__construct('td');
    }
}
<?php
namespace HtmlTag\Table;

use HtmlTag\HtmlTag;

class TableRow extends HtmlTag
{
    public function __construct() {
        parent::__construct('tr');
    }
}
<?php
namespace HtmlTag\Table;

use HtmlTag\HtmlTag;

class TableBody extends HtmlTag
{
    public function __construct() {
        parent::__construct('tbody');
    }
}
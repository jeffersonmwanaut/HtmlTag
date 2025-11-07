<?php
namespace HtmlTag\Table;

use HtmlTag\HtmlTag;

class TableFoot extends HtmlTag
{
    public function __construct() {
        parent::__construct('tfoot');
    }
}
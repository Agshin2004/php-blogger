<?php

namespace App;

function inBookmark(bool $added) 
{
    return $added ? 'bookmark-added' : '';
}
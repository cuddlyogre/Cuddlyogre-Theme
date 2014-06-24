<?php

$title = 'Posts tagged with ' . allTags();
showTitle($title, 'pagetitle');

//http://wordpress.org/support/topic/multiple-tags-pages
function allTags()
{
    $nice_tag_query = $_SERVER['REQUEST_URI'];
    $nice_tag_query = str_replace('/tag/', '', $nice_tag_query);
    $nice_tag_query = str_replace(',', ', ', $nice_tag_query);
    $nice_tag_query = str_replace('/', '', $nice_tag_query);
    return $nice_tag_query;
}

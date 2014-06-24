<?php

function returnedPostsCount()
{
    global $wp_query;
    return $wp_query->post_count;
}

function postNumber()
{
    global $wp_query;
    return $wp_query->current_post;
}

function isFirstPost()
{
    return postNumber() == 0;
}

function isLastPost()
{
    return postNumber() == returnedPostsCount() - 1;
}

function oddOrEven()
{
    if (postNumber() % 2 == 0)
    {
        return "evenPost";
    }
    else
    {
        return "oddpost";
    }
}

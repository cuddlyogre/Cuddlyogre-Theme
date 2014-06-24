<?php
if (is_date())
{
    if (is_year())
    {
        $date = get_the_time('Y');
    }
    elseif (is_month())
    {
        $date = get_the_time('F, Y');
    }
    elseif (is_day())
    {
        $date = get_the_time('F j, Y');
    }

    $title = 'Posts for ' . $date;
}

showTitle($title, 'pagetitle');

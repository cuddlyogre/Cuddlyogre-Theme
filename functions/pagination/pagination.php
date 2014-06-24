<?php

function canPaginate()
{
    //wordpress.stackexchange.com/questions/9186/how-to-determine-if-theres-a-next-page CRAP THAT WAS HARD
    $prev_link = get_previous_posts_link(__('&laquo; Older Entries'));
    $next_link = get_next_posts_link(__('Newer Entries &raquo;'));

    return $prev_link || $next_link;
}

function canCatPaginate()
{
    ob_start();
    previous_post_link('&laquo; %link', '%title', FALSE, $_SESSION['categories']);
    $catprev = ob_get_contents();
    ob_clean();

    ob_start();
    next_post_link('%link &raquo;', '%title', FALSE, $_SESSION['categories']);
    $catnext = ob_get_contents();
    ob_clean();

    return $catprev || $catnext;
}

function pagination($texttype)
{
    ?>
    <span class="<?php echo $texttype; ?>">
        <ul>
            <li class="next"><?php previous_posts_link('Newer &raquo;') ?></li>
            <li class="prev"><?php next_posts_link('&laquo; Older') ?></li>
        </ul>
    </span>
    <?php
}

function catpagination($texttype)
{
    ?>
    <span class="<?php echo $texttype; ?>"> 
        <ul> 
            <li class="next"><?php next_post_link('%link &raquo;', '%title', FALSE, $_SESSION['categories']); ?> </li> 
            <li class="prev"><?php previous_post_link('&laquo; %link', '%title', FALSE, $_SESSION['categories']); ?>  </li> 
        </ul> 
    </span>
    <?php
}

function paginationStyleSetup()
{
    wp_enqueue_style('paginationStyle', get_template_directory_uri() . '/functions/pagination/pagination.css');
}

add_action('wp_enqueue_scripts', 'paginationStyleSetup', 1);

function setPaginationSessionVariables()
{
    $_SESSION['categories'] = getCatsNotAssignedTo(get_the_ID());
}

function unsetPaginationSessionVariables()
{
    unset($_SESSION['categories']);
}

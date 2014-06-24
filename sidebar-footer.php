<?php

if (
        isSidebarUsed(SIDEBAR_FOOTER_ONE) ||
        isSidebarUsed(SIDEBAR_FOOTER_TWO) ||
        isSidebarUsed(SIDEBAR_FOOTER_THREE) ||
        isSidebarUsed(SIDEBAR_FOOTER_FOUR)
)
{
    createSidebar(SIDEBAR_FOOTER_ONE, 'sidebar sidebarfooter');
    createSidebar(SIDEBAR_FOOTER_TWO, 'sidebar sidebarfooter');
    createSidebar(SIDEBAR_FOOTER_THREE, 'sidebar sidebarfooter');
    createSidebar(SIDEBAR_FOOTER_FOUR, 'sidebar sidebarfooter');
}
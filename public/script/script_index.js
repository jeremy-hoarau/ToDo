function changeTab(tab)
{
    if(tab === 'shared lists')
    {
        $("#SharedLists").css("display", "block");
        $("#MyLists").css("display", "none");
    }
    else
    {
        $("#MyLists").css("display", "block");
        $("#SharedLists").css("display", "none");
    }
    TabToggleClasses();

}

function TabToggleClasses()
{
    $(".tab-button").toggleClass("back-color-0 border-color-0 color-4 border-color-4 color-4 back-color-1");
    DarkMode();
}


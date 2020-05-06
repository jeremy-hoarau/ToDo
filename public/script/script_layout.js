function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return null;
}

function DarkModeSetup()
{
    if(getCookie("dark_mode") == null)
        setCookie("dark_mode", false, 365);
    if(getCookie("dark_mode") === 'true')
    {
        $("#DarkMode").prop('checked', true);
        DarkColors();
    }
    else
    {
        LightColors();
    }
}

function DarkMode() {
    if($('#DarkMode').is(":checked"))
    {
        setCookie("dark_mode", 'true', 365);
        DarkColors();
    }
    else
    {
        setCookie("dark_mode", 'false', 365);
        LightColors();
    }
}

function DarkColors()
{
    //color
    $(".color-0").css("color", "#202D4C");
    $(".color-1").css("color", "#35405A");
    $(".color-2").css("color", "#4C5873");
    $(".color-3").css("color", "#69738B");
    $(".color-4").css("color", "#8F96A6");

    //background-color
    $(".back-color-0").css("background-color", "#202D4C");
    $(".back-color-1").css("background-color", "#35405A");
    $(".back-color-2").css("background-color", "#4C5873");
    $(".back-color-3").css("background-color", "#69738B");
    $(".back-color-4").css("background-color", "#8F96A6");

}

function LightColors()
{
    //color
    $(".color-0").css("color", "#8F96A6");
    $(".color-1").css("color", "#69738B");
    $(".color-2").css("color", "#4C5873");
    $(".color-3").css("color", "#35405A");
    $(".color-4").css("color", "#202D4C");

    //background-color
    $(".back-color-0").css("background-color", "#8F96A6");
    $(".back-color-1").css("background-color", "#69738B");
    $(".back-color-2").css("background-color", "#4C5873");
    $(".back-color-3").css("background-color", "#35405A");
    $(".back-color-4").css("background-color", "#202D4C");

}
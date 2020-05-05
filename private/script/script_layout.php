<?php
?>
<script>
    function DarkModeSetup()
    {
        var dark_mode = <?php echo $_SESSION['dark_mode']; ?> ;
        if(dark_mode)
        {
            $("#DarkMode").checked = true;
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
            <?php $_SESSION['dark_mode'] = 1?>
            DarkColors();
        }
        else
        {
            <?php $_SESSION['dark_mode'] = 0 ?>
            LightColors();
        }
    }

    function DarkColors()
    {
        $(".color-primary-0").css("color", "#202D4C");
        $(".color-primary-1").css("color", "#35405A");
        $(".color-primary-2").css("color", "#69738B");
        $(".color-primary-3").css("color", "#8F96A6");
        $(".color-primary-4").css("color", "#4C5873");
    }

    function LightColors()
    {
        $(".color-primary-0").css("color", "#4C5873");
        $(".color-primary-1").css("color", "#8F96A6");
        $(".color-primary-2").css("color", "#69738B");
        $(".color-primary-3").css("color", "#35405A");
        $(".color-primary-4").css("color", "#202D4C");
    }
</script>
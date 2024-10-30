jQuery(".choco-calendar.welcome-panel a.welcome-panel-close.button").click(function () {
    jQuery.cookie("choco-calendar-welcome-panel", "hidden", {expires: 20});
    jQuery("#welcome-panel.choco-calendar").addClass("hidden");
    return false;
});

jQuery(".choco.welcome-panel a.welcome-panel-close.button").click(function () {
    jQuery.cookie("choco-welcome-panel", "hidden", {expires: 20});
    jQuery("#welcome-panel.choco").addClass("hidden");
    return false;
});

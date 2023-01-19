var safeColors = ['00', '33', '66', '99', 'cc', 'ff'];
var rand = function() {
    return Math.floor(Math.random()*6);
};
var randomColor = function() {
    var r = safeColors[rand()];
    var g = safeColors[rand()];
    var b = safeColors[rand()];
    return "#"+r+g+b;
};
jQuery('.tag-name').each(function() {
    jQuery(this)
        .css('text-shadow', '1px 1px 0 rgba(var(--bgc-rgb-a), 1)')
        .parent()
        .css('color', randomColor());
});
$(document).ready(function () {
    // Initialize Bootstrap tabs
    $('#myTabs a').on('click', function (e) {
        e.preventDefault();
        var tabId = $(this).attr('href');
        showTab(tabId);
    });

    // Show the default tab (Upcoming)
    $('#myTabs a:first').tab('show');
});

// Dynamically show a tab
function showTab(tabId) {
    $('.tab-pane').removeClass('show active');
    $(tabId).addClass('show active');
}

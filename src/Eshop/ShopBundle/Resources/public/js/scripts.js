$(function () {
    // Remove Search if user Resets Form or hits Escape!
    $('body, .navbar-collapse form[role="search"] button[type="reset"]').on('click keyup', function(event) {
        if (event.which == 27 && $('.navbar-collapse form[role="search"]').hasClass('active') ||
            $(event.currentTarget).attr('type') == 'reset') {
            closeSearch();
        }
    });

    function closeSearch() {
        var $form = $('.navbar-collapse form[role="search"].active')
        $form.find('input').val('');
        $form.removeClass('active');
    }

    // Show Search if form is not active // event.preventDefault() is important, this prevents the form from submitting
    $(document).on('click', '.navbar-collapse form[role="search"]:not(.active) button[type="submit"]', function(event) {
        event.preventDefault();
        var $form = $(this).closest('form'),
            $input = $form.find('input');
        $form.addClass('active');
        $input.focus();

    });
    // ONLY FOR DEMO // Please use $('form').submit(function(event)) to track from submission
    $(document).on('click', '.navbar-collapse form[role="search"].active button[type="submit"]', function(event) {
        event.preventDefault();
        var search_phrase = $('#search_phrase').val();
        //check if search is empty
        if (search_phrase === '') {
            closeSearch();
        } else {
            $('form').submit();
        }
    });

    //grid to list view changing
    $('#list').click(function (event) {
        event.preventDefault();
        $('#products .item').addClass('list-group-item');
    });
    $('#grid').click(function (event) {
        event.preventDefault();
        $('#products .item').removeClass('list-group-item');
        $('#products .item').addClass('grid-group-item');
    });

    //menu items highlight
    highlightMenu();
});

//menu items highlight
function highlightMenu(){
    //get requestUri
    var requestUri = '{{ app.request.requestUri }}';

    //highlight current route
    $('.menu-link').each(function () {
        if ($(this).attr('href') == requestUri) {
            $(this).addClass('active');
        }
    });
}
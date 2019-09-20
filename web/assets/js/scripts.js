$(function () {
    // Remove Search if user Resets Form or hits Escape!
    $('body, .navbar-collapse form[role="search"] button[type="reset"]').on('click keyup', function (event) {
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
    $(document).on('click', '.navbar-collapse form[role="search"]:not(.active) button[type="submit"]', function (event) {
        event.preventDefault();
        var $form = $(this).closest('form'),
            $input = $form.find('input');
        $form.addClass('active');
        $input.focus();

    });
    // ONLY FOR DEMO // Please use $('form').submit(function(event)) to track from submission
    $(document).on('click', '.navbar-collapse form[role="search"].active button[type="submit"]', function (event) {
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

    //get last seen products
    getLastSeenProducts();

    //get last seen products
    likesInit();
});

function changeGlyphicon(clickedIcon) {
    if (clickedIcon.hasClass('glyphicon-heart-empty')) {
        clickedIcon.removeClass('glyphicon-heart-empty');
        clickedIcon.addClass('glyphicon-heart');
    } else {
        clickedIcon.removeClass('glyphicon-heart');
        clickedIcon.addClass('glyphicon-heart-empty');
    }
}

function checkProductIsLiked(productId) {
    $.ajax({
        type: 'post',
        url: urls['ajax_is_liked_product'],
        data: {product_id: productId},
        success: function (data) {
            if (data.liked === true) {
                var icon = $('.like');
                changeGlyphicon(icon);
            }
        },
        error: function (data) {
            if (data.message) {
                alert(messages[data.message]);
            } else if (data.responseJSON.message) {
                alert(messages[data.responseJSON.message]);
            }
        }
    });
}

function getLastSeenProducts() {
    $.ajax({
        type: 'post',
        url: urls['ajax_get_last_seen_products'],
        success: function (data) {
            if (data.success === true) {
                $(data.html).appendTo('#latest-products');
            }
        }
    });
}

function likesInit() {
    //like click handle
    $(document).on('click', '.like', function (e) {
        e.preventDefault();
        var clickedIcon = $(this);
        var productId = $(this).parent().parent().data('id');
        //send ajax
        $.ajax({
            type: 'post',
            url: urls['ajax_like'],
            data: {product_id: productId},
            success: function (data) {
                if (data.success === true) {
                    changeGlyphicon(clickedIcon);
                }
            },
            error: function (data) {
                if (data.message) {
                    alert(messages[data.message]);
                } else if (data.responseJSON.message) {
                    alert(messages[data.responseJSON.message]);
                }
            }
        });
    });
}

function addToLastSeenProductIds(productId) {
    var arr;

    //get all
    if (Cookies.get('last-seen')) {
        arr = JSON.parse(Cookies.get('last-seen'));
        //search in array
        var index = arr.indexOf(productId);

        //if in center of array
        if (index >= 0) {
            arr.splice(index, 1);
        }

        //add to first position
        arr.unshift(productId);

        //control array length
        arr.splice(10);
    } else {
        //create new arr
        arr = [];
        arr.unshift(productId);
    }
    Cookies.set('last-seen', JSON.stringify(arr));
}


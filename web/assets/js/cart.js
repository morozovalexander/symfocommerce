$(document).ready(function () {
    $('[data-toggle="confirmation"]').confirmation();

    $(document).on('click', '.addtocart-btn', function (e) {
        e.preventDefault();
        //define var
        var cartObj;

        //get all cart
        if (Cookies.get('cart')) {
            cartObj = JSON.parse(Cookies.get('cart'));
        } else {
            cartObj = {};
        }

        //get new vars
        var productRow = $(this).closest('.id-row');

        var productIdRaw = productRow.data('id');
        var productId = toPositiveInt(productIdRaw);

        var productQuantityRaw = productRow.find('.addtocart-input').val();
        var productQuantity = toPositiveInt(productQuantityRaw);
        if (isNaN(productQuantity)) {
            productQuantity = 1;
        }

        var productPriceRaw = productRow.parent().find('.price span').text();
        var productPrice = toPositiveInt(productPriceRaw);
        addToNavbarCart(productQuantity, productPrice);

        //record to cart
        if (cartObj[productId]) {
            cartObj[productId] = cartObj[productId] + productQuantity;
        } else {
            cartObj[productId] = productQuantity;
        }

        //set cookie
        Cookies.set('cart', JSON.stringify(cartObj));
    });

    $('.product-remove').on('click', function(e){
        e.preventDefault();
        var productRecord = $(this).closest('tr');
        productRecord.remove();

        recalculateCart();
    });

    $('.clear-cart').on('click', function(e){
        e.preventDefault();
        $('.product-position').each(function () {
                $(this).remove();
            }
        );
        recalculateCart();
    });

    $(".quantity").bind('keyup change click', function (e) {
        if (! $(this).data("previousValue") || $(this).data("previousValue") != $(this).val()) {
            $(this).data("previousValue", $(this).val());

            //if quantity changed
            recalculateCart();
        }
    });

    $(".quantity").each(function () {
        $(this).data("previousValue", $(this).val());
    });
});

function recalculateCart(){
    var totalSum = 0;
    var totalQuantity = 0;
    var cartObj = {};

    $('.product-position').each(function () {
        var quantityInput = $(this).find('.quantity');

        //get all values
        var productId = toPositiveInt(quantityInput.data('id'));
        var productQuantity = toPositiveInt(quantityInput.val());
        var productPrice = toPositiveInt($(this).find('.price span').text());
        var productSum = productPrice * productQuantity;

        //show new sum
        var productSumSelector = $(this).find('.sum');
        productSumSelector.html(productSum);

        //record to obj
        cartObj[productId] = productQuantity;

        totalSum += productSum;
        totalQuantity += productQuantity;
    });

    //show new total sum
    var totalSumSelector = $('.totalsum');
    totalSumSelector.html(totalSum);

    //update navbar cart
    updateNavbarCart(totalQuantity, totalSum);

    //update cookies
    Cookies.remove('cart');
    Cookies.set('cart', JSON.stringify(cartObj));
}

function toPositiveInt(rawVal) {
    return Math.abs(Math.round(rawVal));
}


function addToNavbarCart(quantity, price){
    //find selectors
    var quantitySelector = $('#cart-quantity');
    var sumSelector = $('#cart-sum');

    var oldQuantity = toPositiveInt(quantitySelector.text());
    var oldSum = toPositiveInt(sumSelector.text());

    if (quantity > 0 && price > 0) {
        var newQuantity = oldQuantity + quantity;
        var newSum = oldSum + (price * quantity);

        quantitySelector.text(newQuantity);
        sumSelector.text(newSum);
    }
}

function updateNavbarCart(totalQuantity, totalSum){
    //find selectors
    var quantitySelector = $('#cart-quantity');
    var sumSelector = $('#cart-sum');

    //show new values
    quantitySelector.text(totalQuantity);
    sumSelector.text(totalSum);
}
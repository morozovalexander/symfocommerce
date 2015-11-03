$(document).ready(function () {
    $('.addtocart-btn').on('click', function(e){
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
        var productRow = $(this).parent().parent().parent();

        var productIdRaw = productRow.data('id');
        var productId = toPositiveInt(productIdRaw);

        var productQuantityRaw = productRow.find('.addtocart-input').val();
        var productQuantity = toPositiveInt(productQuantityRaw);

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
});

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

function toPositiveInt(rawVal) {
    return Math.abs(Math.round(rawVal));
}
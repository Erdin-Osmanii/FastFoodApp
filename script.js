$(document).ready(function() {
    var totalPrice = parseFloat(sessionStorage.getItem('totalPrice')) || 0;

    $('.add-to-cart').on('click', function() {
        var name = $(this).data('name');
        var price = parseFloat($(this).data('price'));
        var item = "<div class='cartitem'>" + name + ": " + price.toFixed(2) + "€ <button class='remove-item'>Largo nga shporta</button></div>";
        $('#chart').append(item);

        totalPrice += price;
        updateTotalPrice();
        saveCartItems();
    });

    $('#chart').on('click', '.remove-item', function() {
        var item = $(this).parent();
        var price = parseFloat(item.contents().get(0).nodeValue.match(/(\d+\.\d+)/)[0]);
        item.remove();

        totalPrice -= price;
        updateTotalPrice();
        saveCartItems();
    });

    function updateTotalPrice() {
        $('#total').text(totalPrice.toFixed(2));
    }

    function saveCartItems() {
        var cartItems = $('#chart').html();
        sessionStorage.setItem('cartItems', cartItems);
        sessionStorage.setItem('totalPrice', totalPrice.toFixed(2));
    }

    function loadCartItems() {
        var cartItems = sessionStorage.getItem('cartItems');
        if (cartItems) {
            $('#chart').html(cartItems);
        }
    }

    function clearCart() {
        $('#chart').empty();
        totalPrice = 0;
        updateTotalPrice();
        saveCartItems();
    }

    function completeOrder() {
        alert('Pagesa u krye me sukses dhe porosia u dërgua. Faleminderit!');
        clearCart();
    }

    function initiatePayPalCheckout() {
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: totalPrice.toFixed(2),
                            currency_code: 'EUR'
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                // Check if the payment is successful
                completeOrder();
            }
        }).render('#checkout');

        // Disable the PayPal button after it is clicked
        $('#paypal-button').prop('disabled', true);
    }

    function showAddressForm() {
        $('#address-form').show();
    }

    function hideAddressForm() {
        $('#address-form').hide();
    }

    function getAddress() {
        return $('#address').val();
    }

    function setAddress(address) {
        $('#user-address').text('Adresa: ' + address);
    }

    function isUserLoggedIn() {
        return "<?php echo $loggedIn ? 'true' : 'false'; ?>";
    }

    function handleCashPayment() {
        if ($('#chart').is(':empty')) {
            return; // Do nothing if the cart is empty
        }
        
        if (isUserLoggedIn()) {
            var address = getAddress();
            if (address) {
                setAddress(address);
                alert('Porosia u dërgua. Faleminderit!');
                clearCart();
            } else {
                alert('Ju lutem vendosni adresën tuaj.');
            }
        } else {
            showAddressForm();
        }
    }

    function handlePayPalPayment() {
        if ($('#chart').is(':empty')) {
            return; // Do nothing if the cart is empty
        }

        if (isUserLoggedIn()) {
            initiatePayPalCheckout();
        } else {
            showAddressForm();
        }
    }

    $('#cash-button').on('click', function() {
        handleCashPayment();
    });

    $('#paypal-button').on('click', function() {
        handlePayPalPayment();
    });

    // Hide the address form initially
    hideAddressForm();

    // Initial update of the total price and cart items
    updateTotalPrice();
    loadCartItems();
});
(function ($) {
    // Update quantity
    $(".item-quantity").on("change", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        $.ajax({
            url: "/cart/" + id, // data-id
            method: "PUT",
            data: {
                quantity: $(this).val(),
                _token: csrf_token,
            },
        });
    });

    // Remove item from cart
    $(".remove-item").on("click", function (e) {
        let id = $(this).data("id");
        e.preventDefault();
        $.ajax({
            url: "/cart/" + id,
            method: "delete",
            data: {
                _token: csrf_token,
                product_id: $(this).data("id"),
            },
            success: (response) => {
                $(`#${id}`).remove();
            },
        });
    });

    $(".add-to-cart-btn").on("click", function (e) {
        e.preventDefault();
        let product_id = $(this).data("productId");
        let quantity = $(".quantity-select").val();
        $.ajax({
            url: "/cart",
            method: "POST",
            data: {
                _token: csrf_token,
                quantity: quantity,
                product_id: product_id,
            },
            success: (response) => {
                alert("✅Product added to cart!");
            },
        });
    });
})(jQuery);

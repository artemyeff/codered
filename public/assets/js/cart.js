$(document).ready(function () {

  $(document).on('click', '.cart-minus', function () {
    $.ajax({
      method: 'POST',
      url: "http://localhost/api/v1/cart/minus",
      dataType: 'json',
      contentType: 'application/json',
      data: JSON.stringify({
        product: {
          id: $(this).data('productId')
        }
      })
    }).done(function (data) {
      if (data.html === '') {
        $('.cont-cart').html('')
      } else {
        $('#cart-content').html(data.html);
      }
    });
  })

  $(document).on('click', '.cart-plus', function () {
    $.ajax({
      method: 'POST',
      url: "http://localhost/api/v1/cart/plus",
      dataType: 'json',
      contentType: 'application/json',
      data: JSON.stringify({
        product: {
          id: $(this).data('productId')
        }
      })
    }).done(function (data) {
      $('#cart-content').html(data.html);
    });
  })

  $(document).on('click', '.cart-remove', function () {
    $.ajax({
      method: 'POST',
      url: "http://localhost/api/v1/cart/remove",
      dataType: 'json',
      contentType: 'application/json',
      data: JSON.stringify({
        product: {
          id: $(this).data('productId')
        }
      })
    }).done(function (data) {
      if (data.html === '') {
        $('.cont-cart').html('')
      } else {
        $('#cart-content').html(data.html);
      }
    });
  })

  $(document).on('click', '#order-create', function () {
    $.ajax({
      method: 'POST',
      url: "http://localhost/api/v1/orders",
      dataType: 'json',
      contentType: 'application/json',
      data: JSON.stringify({
        phone: $('#order-phone').val()
      })
    }).done(function (data) {
      window.location.replace('/')
    });
  })
})

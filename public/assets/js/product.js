$(document).ready(function () {
  var $counter = $('#cart-counter')
  var $buyBtn = $('#cart-buy')

  $buyBtn.click(function () {
    $.ajax({
      method: 'POST',
      url: "http://localhost/api/v1/cart/add",
      dataType: 'json',
      contentType: 'application/json',
      data: JSON.stringify({
        product: {
          id: $buyBtn.data('productId')
        }
      })
    }).done(function (data) {
      $counter.text('(' + data.count + ')')
    });
  })
})

var interval = null;
$(document).ready(function () {
  getKitchenAll();
  updateDiv();
});

function getKitchenAll() {
  $.ajax({
    url: "index.php",
    method: "POST",
    dataType: "text",
    data: {
      getAll: 1,
    },
    success: function (e) {
      $("#content-show").html(e);

      //หมด
      $(".btn-finish").click(function () {
        let orderDetailIdInput = $(
          `.input-order-detail-id[data-id='${$(this).data("id")}']`
        );
        let orderDetailId = orderDetailIdInput.val();
        let foodIdInput = $(`.input-food-id[data-id='${$(this).data("id")}']`);
        let foodlId = foodIdInput.val();

        $.ajax({
          url: "index.php",
          method: "POST",
          dataType: "text",
          data: {
            foodFinish: 1,
            orderDetailId2: orderDetailId,
            foodId2: foodlId,
          },
          success: function (e) {
            getKitchenAll();
          },
        });
      });

      //ยกเลิกอาหาร
      $(".btn-cancel-k").click(function () {
        let orderDetailIdInput2 = $(this).attr("data-id");
        $.ajax({
          url: "index.php",
          method: "POST",
          dataType: "text",
          data: {
            cancelfood: 1,
            orDetailId: orderDetailIdInput2,
          },
          success: function (e) {
            getKitchenAll();
          },
        });
      });

      //เริ่มทำ
      $(".btn-start-do-it").click(function () {
        let orderDetailIdInput = $(
          `.input-order-detail-id[data-id='${$(this).data("id")}']`
        );
        let orderDetailId = orderDetailIdInput.val();
        $.ajax({
          url: "index.php",
          method: "POST",
          dataType: "text",
          data: {
            startDoTi: 1,
            orDetailId: orderDetailId,
          },
          success: function (e) {
            getKitchenAll();
          },
        });
      });
      //สำเร็จ
      $(".btn-do-success").click(function () {
        let orderDetailIdInput = $(
          `.input_success[data-id='${$(this).data("id")}']`
        );
        let orderDetailId = orderDetailIdInput.val();
        $.ajax({
          url: "index.php",
          method: "POST",
          dataType: "text",
          data: {
            success: 1,
            orDetailId: orderDetailId,
          },
          success: function (e) {
            getKitchenAll();
          },
        });
      });
    },
  });
}

function updateDiv() {
  $.ajax({
    url: "index.php",
    method: "POST",
    dataType: "text",
    data: {
      checkOrderDetail: 1,
    },
    success: function (e) {
      var numFirst = parseInt(e);
      updateDiv2(numFirst);
    },
  });
}

function updateDiv2(numFirst) {
  $.ajax({
    url: "index.php",
    method: "POST",
    dataType: "text",
    data: {
      checkOrderDetail2: 1,
    },
    success: function (e) {
      var numSec = parseInt(e);
      if (numFirst == numSec) {
        setTimeout(() => {
          updateDiv2(numFirst);
        }, 1000);
      } else {
        setTimeout(() => {
          updateDiv();
        }, 1000);
        getKitchenAll();
      }
    },
  });
}

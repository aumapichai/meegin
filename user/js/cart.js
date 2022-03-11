var interval = null;
$(document).ready(function () {
  getFoodCart();
  getAllCountCart();
  interval = setInterval(updateDiv, 1000);
  //เรียกพนักงาน
  $(".btn-call-staff").click(function () {
    let tableNum = $(this).attr("data-id");
    Swal.fire({
      title: "เรียกพนักงาน",
      text: "ยืนยันการเรียกพนักงาน!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ยืนยัน",
      cancelButtonText: "ยกเลิก",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "cart.php",
          method: "POST",
          dataType: "text",
          data: {
            callStaff: 1,
            tableNum: tableNum,
          },
          success: function (e) {
            Swal.fire("ยืนยันแล้ว", "กรุณารอสักครู่", "success");
          },
        });
      }
    });
  });
});

function getFoodCart() {
  $.ajax({
    url: "cart.php",
    method: "POST",
    dataType: "text",
    data: {
      getAllFCart: 1,
    },
    success: function (e) {
      $("#show-all-cart").html(e);
      //ลบ
      $(".btn-delete-food").click(function () {
        let inputCartId = $(`.food_id_class[data-id='${$(this).data("id")}']`);
        let cartId = inputCartId.val();
        Swal.fire({
          title: "คุณแน่ใจใช่หรือไม่?",
          text: "คุณต้องการลบรายการนี้จริงหรือไม่!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "ยืนยัน",
          cancelButtonText: "ยกเลิก",
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "cart.php",
              method: "POST",
              dataType: "text",
              data: {
                deleteFoodCart: 1,
                cartIdd: cartId,
              },
              success: function (e) {
                getFoodCart();
                getAllCountCart();
              },
            });
            Swal.fire("สำเร็จ!", "ทำการลบรายการอาหารสำเร็จ.", "success");
          }
        });
      });

      //เพิ่มรายละเอียด
      $(".btn-add-detail-food").click(function () {
        let inputCartId2 = $(`.food_id_class[data-id='${$(this).data("id")}']`);
        let cartId2 = inputCartId2.val();
        Swal.fire({
          title: "กรุณาระบุรายละเอียด",
          input: "text",
          inputAttributes: {
            autocapitalize: "off",
          },
          showCancelButton: true,
          confirmButtonText: "ยืนยัน",
          cancelButtonText: "ยกลิก",
        }).then((result) => {
          if (result.isConfirmed) {
            let textDetail = result.value;
            $.ajax({
              url: "cart.php",
              method: "POST",
              dataType: "text",
              data: {
                addDetailCart: 1,
                cartId2: cartId2,
                txtDetail: textDetail,
              },
              success: function (e) {
                getFoodCart();
              },
            });
            Swal.fire("สำเร็จ!", "ทำการเพิ่มรายละเอียดสำเร็จ.", "success");
          }
        });
      });

      //ลบทั้งหมด
      $(".btn-delete-all").click(function () {
        Swal.fire({
          title: "คุณแน่ใจใช่หรือไม่?",
          text: "คุณต้องการลบรายการทั้งหมดจริงหรือไม่!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "ยืนยัน",
          cancelButtonText: "ยกเลิก",
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "cart.php",
              method: "POST",
              dataType: "text",
              data: {
                deleteFoodCartAll: 1,
              },
              success: function (e) {
                getFoodCart();
                getAllCountCart();
              },
            });
            Swal.fire("สำเร็จ!", "ทำการลบรายการอาหารทั้งหมดสำเร็จ.", "success");
          }
        });
      });

      //เพิ่มจำนวน
      $(".btn-add").click(function () {
        let inputCartId3 = $(`.food_id_class[data-id='${$(this).data("id")}']`);
        let cartId3 = inputCartId3.val();
        let inputPrice = $(`.price_unit_2[data-id='${$(this).data("id")}']`);
        let priceUnit = inputPrice.val();
        let inputQuanity = $(`.input_qty[data-id='${$(this).data("id")}']`);
        let totalShow = $(`.price_total_food[data-id='${$(this).data("id")}']`);
        if (inputQuanity.val() >= 1 && inputQuanity.val() < 5) {
          inputQuanity.val(function (i, oldval) {
            return ++oldval;
          });
        }

        let quanity = inputQuanity.val();
        let total = priceUnit * quanity;
        $.ajax({
          url: "cart.php",
          method: "POST",
          dataType: "text",
          data: {
            addQuanity: 1,
            cartId3: cartId3,
            quanity: quanity,
          },
          success: function (e) {
            totalShow.html(total);
            $(".change_qty_" + cartId3).val(quanity);
          },
        });
      });
      //ลดจำนวน
      $(".btn-minus").click(function () {
        let inputCartId4 = $(`.food_id_class[data-id='${$(this).data("id")}']`);
        let cartId4 = inputCartId4.val();
        let inputPrice2 = $(`.price_unit_2[data-id='${$(this).data("id")}']`);
        let priceUnit2 = inputPrice2.val();
        let inputQuanity2 = $(`.input_qty[data-id='${$(this).data("id")}']`);
        let totalShow2 = $(
          `.price_total_food[data-id='${$(this).data("id")}']`
        );
        if (inputQuanity2.val() > 1) {
          inputQuanity2.val(function (i, oldval) {
            return --oldval;
          });
        }

        let quanity2 = inputQuanity2.val();
        let total2 = priceUnit2 * quanity2;
        $.ajax({
          url: "cart.php",
          method: "POST",
          dataType: "text",
          data: {
            addQuanity: 1,
            cartId3: cartId4,
            quanity: quanity2,
          },
          success: function (e) {
            totalShow2.html(total2);
            $(".change_qty_" + cartId4).val(quanity2);
          },
        });
      });
      $(".btn-confirem-order").click(function () {
        Swal.fire({
          title: "ยืนยัน",
          text: "ยืนยันการซื้อ!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "ยืนยัน",
          cancelButtonText: "ยกเลิก",
        }).then((result) => {
          if (result.isConfirmed) {
            $("#form_confirm_order").submit();
          }
        });
      });
    },
  });
}

function getAllCountCart() {
  $.ajax({
    url: "cart.php",
    method: "POST",
    dataType: "text",
    data: {
      getAllCountCart: 1,
    },
    success: function (e) {
      $("#show-cart").html(e);
    },
  });
}

function updateDiv() {
  $.ajax({
    url: "cart.php",
    method: "POST",
    dataType: "text",
    data: {
      chekExpire: 1,
    },
    success: function (e) {
      var num = parseInt(e);
      if (num == 0) {
        window.location = "expire_destroy.php";
      }
      // clearInterval(interval);
    },
  });
}

$(document).ready(function () {
  $(".btn-add-cart").click(function () {
    let queryFoodId = $(`.add_food_id[data-id='${$(this).data("id")}']`);
    let queryTableNumber = $(
      `.add_table_number_c[data-id='${$(this).data("id")}']`
    );
    let food_id = queryFoodId.val();
    let table_number = queryTableNumber.val();
    Swal.fire({
      position: "center",
      icon: "success",
      title: "เพิ่มในตะกร้าแล้ว",
      showConfirmButton: false,
      timer: 1500,
    });
    $(this).find(".img-category-food").css({ border: "3px solid #4caf50" });
    $(this).find(".icon-check-cart").removeClass("icon-display-cart");
    let quanityCart = parseInt($.trim($(".text-count-food").text()));

    $.ajax({
      url: "food_select.php",
      method: "POST",
      dataType: "text",
      data: {
        addCart: 1,
        foodId: food_id,
        tableNumber: table_number,
      },
      success: function (e) {
        if (parseInt(e) == 0) {
          $(".text-count-food").removeClass("icon-display-cart");
          $(".text-count-food").text(quanityCart + 1);
        }
      },
    });
  });
  
});

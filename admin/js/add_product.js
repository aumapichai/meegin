$(document).ready(function () {
  //ค้นหา
  let searchName = $("#search");
  let categoryName = $("#category_select_search");

  searchName.keyup(function () {
    getFoodAll(categoryName.val(), $(this).val());
  });

  categoryName.change(function () {
    getFoodAll($(this).val(), searchName.val());
  });

  $(".btn-add-product-main").click(function () {
    $(".add-product").show();
    $(".btn-cancel-mine").click(function () {
      $(".add-product").hide();
    });
  });


  let foodName = $("#add_food_name");
  let foodFullPrice = $("#add_full_food_price");
  let foodPrice = $("#add_food_price");
  let foodPicture = $("#food_picture");
  let foodCategory = $("#add_food_category");
  let foodStatus = $("#add_food_status");
  $(".btn-save-mine").click(function () {
    if (foodPicture.val().length === 0) {
      $("#pictuerDisplay").addClass("invalid-mine");
    } else if (foodName.val().length === 0) {
      foodName.addClass("invalid-mine");
    } else if (foodFullPrice.val().length === 0) {
      foodFullPrice.addClass("invalid-mine");
    } else if (foodPrice.val().length === 0) {
      foodPrice.addClass("invalid-mine");
    } else {
      let foodNameAdd = foodName.val();
      let foodFullPriceAdd = foodFullPrice.val();
      let fooddPriceAdd = foodPrice.val();
      let foodCategoryAdd = foodCategory.val();
      let foodStatusAdd = foodStatus.val();

      var fd = new FormData();
      var files = $("#food_picture")[0].files;

      // Check file selected or not
      if (files.length > 0) {
        fd.append("food_picture", files[0]);
        $.ajax({
          url: "products.php",
          type: "post",
          data: fd,
          contentType: false,
          processData: false,
          success: function (response) {
            $.ajax({
              url: "products.php",
              method: "POST",
              data: {
                addFood: 1,
                fNameAdd: foodNameAdd,
                foodFullPriceAdd: foodFullPriceAdd,
                fPriceAdd: fooddPriceAdd,
                fPictureAdd: response,
                fCategoryAdd: foodCategoryAdd,
                fStatusAdd: foodStatusAdd,
              },
              success: function (e) {
                Swal.fire({
                  position: "top-end",
                  icon: "success",
                  title: "เพิ่มสำเร็จ",
                  showConfirmButton: false,
                  timer: 1500,
                });
                $("#add_reset_btn").click();
                foodPicture.val("");
                $("#pictuerDisplay").attr("src", "../picture/restaurant.jpg");
                $(".add-product").hide();
                getFoodAll(categoryName.val(), searchName.val());
              },
            });
          },
        });
      }
    }
  });
  foodPicture.change(function () {
    $("#pictuerDisplay").removeClass("invalid-mine");
  });
  foodName.keyup(function () {
    if ($(this).val().lenght !== 0) {
      foodName.removeClass("invalid-mine");
    }
  });
  foodFullPrice.keyup(function () {
    if ($(this).val().lenght !== 0) {
      foodFullPrice.removeClass("invalid-mine");
    }
  });

  foodPrice.keyup(function () {
    if ($(this).val().lenght !== 0) {
      foodPrice.removeClass("invalid-mine");
    }
  });

  getFoodAll();
});

function getFoodAll(category = "", name = "") {
  $.ajax({
    url: "products.php",
    method: "POST",
    data: {
      getFoodAll2: 1,
      categoryS: category,
      nameS: name,
    },
    success: function (e) {
      $(".show_lidt_food_all").html(e);
      //ลบอาหาร
      $(".btn-detail-food").click(function () {
        let foodId = $(this).val();
        Swal.fire({
          title: "ลบรายการอาหาร!",
          text: "คุณต้องการลบรายการนี้หรือไม่?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "ยืนยัน",
          cancelButtonText: "ยกเลิก",
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "products.php",
              method: "POST",
              data: {
                deleteFood: 1,
                foodId: foodId,
              },
              success: function (e) {
                getFoodAll(category, name);
              },
            });
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: "ลบรายการอาหารเรียบร้อย",
              showConfirmButton: false,
              timer: 1500,
            });
          }
        });
      });

      //แก้ไข
      $(".btn-edit-food").click(function () {
        let foodIdEdit = $(this).val();
        $(".show_piture_old_" + foodIdEdit).hide();
        $(".show_edit_piture_new_" + foodIdEdit).show();
        $(".btn-show-edit-" + foodIdEdit).hide();
        $(".btn-cancel-save-show-" + foodIdEdit).show();
        let imgf = $(".img_food_old_" + foodIdEdit);
        let categryF = $(".edit_category_food_" + foodIdEdit);
        let naemF = $(".edit_food_name_" + foodIdEdit);
        let priceF = $(".edit_food_price_" + foodIdEdit);
        priceF.removeClass("discount-line");
        let priceDiscountF = $(".edit_food_price_discount_" + foodIdEdit);
        let statusF = $(".edit_status_" + foodIdEdit);
        let imgOld = imgf.attr("src");
        let categoryOld = categryF.val();
        let nameOld = naemF.val();
        let priceold = priceF.val();
        let discountOdl = priceDiscountF.val();
        let statusOld = statusF.val();
        categryF.attr("disabled", false);
        naemF.attr("disabled", false);
        priceF.attr("disabled", false);
        priceDiscountF.attr("disabled", false);
        statusF.attr("disabled", false);

        //ยกเลิกแก้ไข
        $(".btn-cancel-edit-" + foodIdEdit).click(function () {
          $(".show_piture_old_" + foodIdEdit).show();
          $(".show_edit_piture_new_" + foodIdEdit).hide();
          $(".btn-show-edit-" + foodIdEdit).show();
          $(".btn-cancel-save-show-" + foodIdEdit).hide();
          $(".img_food_edit_" + foodIdEdit).attr("src", imgOld);
          $(".edit_food_price_" + foodIdEdit).addClass("discount-line");
          categryF.val(categoryOld);
          naemF.val(nameOld);
          priceF.val(priceold);
          priceDiscountF.val(discountOdl);
          statusF.val(statusOld);
          categryF.attr("disabled", true);
          naemF.attr("disabled", true);
          priceF.attr("disabled", true);
          priceDiscountF.attr("disabled", true);
          statusF.attr("disabled", true);
          naemF.removeClass("invalid-mine");
          priceF.removeClass("invalid-mine");
        });

        //บันทึการแก้ไข
        $(".btn-save-edit-" + foodIdEdit).click(function () {
          if (naemF.val().length === 0) {
            naemF.addClass("invalid-mine");
          } else if (priceF.val().length === 0) {
            priceF.addClass("invalid-mine");
          } else if (priceDiscountF.val().length === 0) {
            priceDiscountF.addClass("invalid-mine");
          } else {
            let categoryFoodA = categryF.val();
            let nameFoodA = naemF.val();
            let priceFoodA = priceF.val();
            let priceDiscountFoodA = priceDiscountF.val();
            let statusFoodA = statusF.val();
            if ($("#food_picture_edit_" + foodIdEdit).val() == "") {
              $.ajax({
                url: "products.php",
                method: "POST",
                data: {
                  editFoodNoChangImg: 1,
                  foodIdE: foodIdEdit,
                  foodCategoryE: categoryFoodA,
                  foodNameE: nameFoodA,
                  foodPriceE: priceFoodA,
                  foodPriceDisE: priceDiscountFoodA,
                  foodStatusE: statusFoodA,
                },
                success: function (e) {
                  getFoodAll(category, name);
                  Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "แก้ไขรายการอาหารสำเร็จ",
                    showConfirmButton: false,
                    timer: 1500,
                  });
                },
              });
            } else {
              var fd2 = new FormData();
              var files2 = $("#food_picture_edit_" + foodIdEdit)[0].files;

              // Check file selected or not
              if (files2.length > 0) {
                fd2.append("food_pictuer_edit", files2[0]);
                $.ajax({
                  url: "products.php",
                  type: "post",
                  data: fd2,
                  contentType: false,
                  processData: false,
                  success: function (response) {
                    $.ajax({
                      url: "products.php",
                      method: "POST",
                      data: {
                        editFoodChangImg: 1,
                        foodImgEC: response,
                        foodIdEC: foodIdEdit,
                        foodCategoryEC: categoryFoodA,
                        foodNameEC: nameFoodA,
                        foodPriceEC: priceFoodA,
                        foodPriceDisEc: priceDiscountFoodA,
                        foodStatusEC: statusFoodA,
                      },
                      success: function (e) {
                        getFoodAll(category, name);
                        Swal.fire({
                          position: "top-end",
                          icon: "success",
                          title: "แก้ไขรายการอาหารสำเร็จ",
                          showConfirmButton: false,
                          timer: 1500,
                        });
                      },
                    });
                  },
                });
              }
            }
          }
        });
        naemF.keyup(function () {
          if ($(this).val().lenght !== 0) {
            naemF.removeClass("invalid-mine");
          }
        });
        priceF.keyup(function () {
          if ($(this).val().lenght !== 0) {
            priceF.removeClass("invalid-mine");
          }
        });
        priceDiscountF.keyup(function () {
          if ($(this).val().lenght !== 0) {
            priceDiscountF.removeClass("invalid-mine");
          }
        });
      });
    },
  });
}

function triggerClick(e) {
  document.querySelector("#food_picture").click();
}

function displayImage(e) {
  if (e.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      document
        .querySelector("#pictuerDisplay")
        .setAttribute("src", e.target.result);
    };
    reader.readAsDataURL(e.files[0]);
  }
}

function triggerClickEdit(e) {
  document.querySelector("#food_picture_edit_" + e).click();
}

function displayImageEdit(e, id) {
  if (e.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      document
        .querySelector("#pictuerDisplayEdit_" + id)
        .setAttribute("src", e.target.result);
    };
    reader.readAsDataURL(e.files[0]);
  }
}

$(document).ready(function () {
  //ค้นหา
  let searchName = $("#search");
  searchName.keyup(function () {
    getFoodAll($(this).val());
  });

  $(".btn-add-category-main").click(function () {
    $(".add-category").show();
    $(".btn-cancel-mine").click(function () {
      $(".add-category").hide();
    });
  });
  let categoryName = $("#add_category_name");
  let categoryPicture = $("#category_picture");
  let categoryStatus = $("#add_category_status");
  $(".btn-save-mine").click(function () {
    if (categoryPicture.val().length === 0) {
      $("#pictuerDisplay").addClass("invalid-mine");
    } else if (categoryName.val().length === 0) {
      categoryName.addClass("invalid-mine");
    } else {
      let categoryNameAdd = categoryName.val();
      let categoryStatusAdd = categoryStatus.val();

      var fd = new FormData();
      var files = $("#category_picture")[0].files;

      // Check file selected or not
      if (files.length > 0) {
        fd.append("category_picture", files[0]);
        $.ajax({
          url: "category.php",
          type: "post",
          data: fd,
          contentType: false,
          processData: false,
          success: function (response) {
            $.ajax({
              url: "category.php",
              method: "POST",
              data: {
                addCategroy: 1,
                cNameAdd: categoryNameAdd,
                cPictureAdd: response,
                cStatusAdd: categoryStatusAdd,
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
                categoryPicture.val("");
                $("#pictuerDisplay").attr("src", "../picture/restaurant.jpg");
                $(".add-category").hide();
                getFoodAll(searchName.val());
              },
            });
          },
        });
      }
    }
  });
  categoryPicture.change(function () {
    $("#pictuerDisplay").removeClass("invalid-mine");
  });
  categoryName.keyup(function () {
    if ($(this).val().lenght !== 0) {
      categoryName.removeClass("invalid-mine");
    }
  });

  getFoodAll();
});

function getFoodAll(name = "") {
  $.ajax({
    url: "category.php",
    method: "POST",
    data: {
      getCategoryAll: 1,
      nameC: name,
    },
    success: function (e) {
      $(".show_lidt_category_all").html(e);
      //ลบอาหาร
      $(".btn-detail-food").click(function () {
        let categoryId = $(this).val();
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
              url: "category.php",
              method: "POST",
              data: {
                deleteCategory: 1,
                categoryId: categoryId,
              },
              success: function (e) {
                getFoodAll(name);
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
        let naemF = $(".edit_food_name_" + foodIdEdit);
        let statusF = $(".edit_status_" + foodIdEdit);
        let imgOld = imgf.attr("src");
        let nameOld = naemF.val();
        let statusOld = statusF.val();
        naemF.attr("disabled", false);
        statusF.attr("disabled", false);

        //ยกเลิกแก้ไข
        $(".btn-cancel-edit-" + foodIdEdit).click(function () {
          $(".show_piture_old_" + foodIdEdit).show();
          $(".show_edit_piture_new_" + foodIdEdit).hide();
          $(".btn-show-edit-" + foodIdEdit).show();
          $(".btn-cancel-save-show-" + foodIdEdit).hide();
          $(".img_food_edit_" + foodIdEdit).attr("src", imgOld);

          naemF.val(nameOld);

          statusF.val(statusOld);

          naemF.attr("disabled", true);

          statusF.attr("disabled", true);
          naemF.removeClass("invalid-mine");
        });

        //บันทึการแก้ไข
        $(".btn-save-edit-" + foodIdEdit).click(function () {
          if (naemF.val().length === 0) {
            naemF.addClass("invalid-mine");
          } else {
            let nameFoodA = naemF.val();
            let statusFoodA = statusF.val();
            if ($("#food_picture_edit_" + foodIdEdit).val() == "") {
              $.ajax({
                url: "category.php",
                method: "POST",
                data: {
                  editCategoryNoChangImg: 1,
                  categoryIdE: foodIdEdit,
                  categoryNameE: nameFoodA,
                  categorytatusE: statusFoodA,
                },
                success: function (e) {
                  getFoodAll(name);
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
                  url: "category.php",
                  type: "post",
                  data: fd2,
                  contentType: false,
                  processData: false,
                  success: function (response) {
                    $.ajax({
                      url: "category.php",
                      method: "POST",
                      data: {
                        editFoodChangImg: 1,
                        categoryImgEC: response,
                        categoryIdEC: foodIdEdit,
                        categoryNameEC: nameFoodA,
                        categorytatusEC: statusFoodA,
                      },
                      success: function (e) {
                        getFoodAll(name);
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
      });
    },
  });
}

function triggerClick(e) {
  document.querySelector("#category_picture").click();
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

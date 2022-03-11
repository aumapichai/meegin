$(document).ready(function () {
  let search = $("#search");
  let category = $("#category_selected");

  search.keyup(function () {
    getAllFood(category.val(), $(this).val());
  });

  category.change(function () {
    getAllFood($(this).val(), search.val());
  });
  getAllFood();
});

function getAllFood(category = "", search = "") {
  $.ajax({
    url: "manage_food.php",
    method: "POST",
    dataType: "text",
    data: {
      getAllFood: 1,
      category: category,
      searchName: search,
    },
    success: function (e) {
      $(".show_all_food").html(e);
      $(".selected_status_food").change(function () {
        let foodId = $(this).attr("data-id");
        if ($(this).val() == "หมด") {
          $.ajax({
            url: "manage_food.php",
            method: "POST",
            dataType: "text",
            data: {
              finishFood: 1,
              foodId: foodId,
            },
            success: function (e) {
              getAllFood(category, search);
            },
          });
        }
        if ($(this).val() == "แสดง") {
          $.ajax({
            url: "manage_food.php",
            method: "POST",
            dataType: "text",
            data: {
              showFood2: 1,
              foodId2: foodId,
            },
            success: function (e) {
              getAllFood(category, search);
            },
          });
        }
      });
    },
  });
}

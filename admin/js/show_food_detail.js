$(document).ready(function () {
  $(".btn-click-show-detail").click(function () {
    let referenceId = $(this).attr("data-id");
    $("#id_reference").val(referenceId);
    $("#myModalShowFoodDetail").modal("show");
    $("#reference_id_show").html(referenceId);
    $.ajax({
      url: "show_food_detail_ajax.php",
      method: "POST",
      dataType: "text",
      data: {
        getDetailFood: 1,
        referenceIdS: referenceId,
      },
      success: function (e) {
        $(".show_detail_food_class").html(e);
        $("#cash_amount").val($("#cash_amount_DB").val());
      },
    });
  });
  $(".btn-click-show-detail-food-report-3").click(function () {
    let referenceId = $(this).attr("data-id");
    $("#id_reference").val(referenceId);
    $("#myModalShowFoodDetail").modal("show");
    $("#reference_id_show").html(referenceId);
    $.ajax({
      url: "show_food_detail_ajax.php",
      method: "POST",
      dataType: "text",
      data: {
        getDetailFood: 1,
        referenceIdS: referenceId,
      },
      success: function (e) {
        $(".show_detail_food_class").html(e);
        $("#cash_amount").val($("#cash_amount_DB").val());
      },
    });
  });
  $(".btn-print-order").click(function () {
    let referenceId = $("#id_reference").val();
    let cashAmount = $("#cash_amount").val();
    $("#myModalShowFoodDetail").modal("hide");
    if (parseInt(cashAmount) > 0) {
      printExternal(
        "chekbill_print.php?idReference=" +
          referenceId +
          "&cash=" +
          parseInt(cashAmount)
      );
    } else {
      printExternal("chekbill_print.php?idReference=" + referenceId);
    }
  });
});

function printExternal(url) {
  var printWindow = window.open(
    url,
    "Print",
    "left=10, top=10, width=950, height=500, toolbar=0, resizable=0"
  );

  printWindow.addEventListener(
    "load",
    function () {
      if (Boolean(printWindow.chrome)) {
        printWindow.print();
        setTimeout(() => {
          printWindow.close();
        }, 500);
      } else {
        printWindow.print();
        printWindow.close();
      }
    },
    true
  );
}

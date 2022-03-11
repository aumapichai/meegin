$(document).ready(function () {
  //มียอดในลิ้นชักไหม
  $.ajax({
    url: "index.php",
    method: "POST",
    dataType: "text",
    data: {
      checkMoneyDrawer: 1,
    },
    success: function (e) {
      if (parseInt(e) == 0) {
        $("#myModalStartMoney").modal("show");
        $("#inputunmber").val($(this).attr("data-valud"));
      }
    },
  });
  $(".clickModalAddMoney").click(function () {
    $("#myModalStartMoney").modal("show");
    $("#inputunmber").val($(this).attr("data-valud"));
  });
  $(".addmoney").click(function () {
    let add = $("#inputunmber").val();
    let amount = null;
    if (add == "") {
      amount = 0;
    } else {
      if (add < 0) {
        amount = 0;
      } else {
        amount = add;
      }
    }
    $.ajax({
      url: "index.php",
      method: "POST",
      dataType: "text",
      data: {
        addStartMoney: 1,
        amountMoney: amount,
      },
      success: function (e) {
        $("#myModalStartMoney").modal("hide");
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "บันทึกสำเร็จ",
          showConfirmButton: false,
          timer: 1500,
        }).then(() => {
          location.reload();
        });
      },
    });
  });
  //พิมพ์ยอดขาย
  $(".click_print_circulation").click(function () {
    let url = "circulation_print.php";
    printExternal(url);
  });

  //พิมพ์สรุปลิ้นชัก
  $(".click_print_drawer").click(function () {
    let url = "drawer_print.php";
    printExternal(url);
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

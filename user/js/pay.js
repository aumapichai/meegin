var interval = null;
$(document).ready(function () {
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
          url: "pay.php",
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

  //เรียกเช็คบิล
  $(".pay-money").click(function () {
    let tableNum = $(this).val();
    Swal.fire({
      title: "เช็คบิล",
      text: "ยืนยันการเรียกเช็คบิล!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ยืนยัน",
      cancelButtonText: "ยกเลิก",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "pay.php",
          method: "POST",
          dataType: "text",
          data: {
            callPay: 1,
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

function updateDiv() {
  $.ajax({
    url: "pay.php",
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

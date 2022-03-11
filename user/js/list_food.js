var interval = null;
$(document).ready(function () {
  getListAll();
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
          url: "list_food.php",
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

function getListAll() {
  $.ajax({
    url: "list_food.php",
    method: "POST",
    dataType: "text",
    data: {
      getListAll: 1,
    },
    success: function (e) {
      $(".show_list_food").html(e);
    },
  });
}

setInterval(function () {
  getListAll();
}, 500);

function updateDiv() {
  $.ajax({
    url: "list_food.php",
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

// function updateDiv() {
//   $.ajax({
//     url: "index.php",
//     method: "POST",
//     dataType: "text",
//     data: {
//       checkOrderDetail: 1,
//     },
//     success: function (e) {
//       var numFirst = parseInt(e);
//       clearInterval(interval);
//       updateDiv2(numFirst);
//     },
//   });
// }

// function updateDiv2(numFirst) {
//   $.ajax({
//     url: "index.php",
//     method: "POST",
//     dataType: "text",
//     data: {
//       checkOrderDetail: 1,
//     },
//     success: function (e) {
//       var numSec = parseInt(e);
//       if (numFirst == numSec) {
//         updateDiv2(numFirst);
//       } else {
//         getKitchenAll();
//         updateDiv(numFirst);
//       }
//     },
//   });
// }

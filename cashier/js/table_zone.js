$(document).ready(function () {
  //เพิ่มโซน

  // let zoneNumber = $("#zone_number");
  // let zoneStatus = $("#add_zone_status");
  // $(".btn-save-zone").click(function () {
  //   if (zoneNumber.val().length === 0) {
  //     zoneNumber.addClass("invalid-mine");
  //   } else {
  //     let zoneNum = zoneNumber.val();
  //     let zoneS = zoneStatus.val();

  //     $.ajax({
  //       url: "table_and_zone.php",
  //       method: "POST",
  //       data: {
  //         addZone: 1,
  //         zoneNumber: zoneNum,
  //         zoneStatus: zoneS,
  //       },
  //       success: function (data) {
  //         zoneNumber.val("");
  //         $("#reset_status").click();
  //         $(".form-add-zone").hide();
  //         Swal.fire({
  //           position: "top-end",
  //           icon: "success",
  //           title: "บันทึกสำเร็จเรียบร้อยแล้ว",
  //           showConfirmButton: false,
  //           timer: 1500,
  //         });
  //         getZoneAll();
  //       },
  //     });
  //   }
  // });
  // zoneNumber.keypress(function (event) {
  //   var ew = event.which;

  //   if (48 <= ew && ew <= 57) return true;

  //   return false;
  // });
  // zoneNumber.keyup(function () {
  //   if ($(this).val().length !== 0) {
  //     zoneNumber.removeClass("invalid-mine");
  //   }
  // });

  // $(".btn-add-table").prop("disabled", true);

  // $(".btn-add-table").click(function () {
  //   $(".add_table").show();
  //   $(".btn-cancel-table").click(function () {
  //     $(".add_table").hide();
  //   });
  // });

  // let table_number1 = $(".add_table_number");
  // let table_status1 = $(".add_tatus_status");
  // let zoneStatust = $(".add_table_zone");
  // $(".btn-add-table-t").click(function () {
  //   let zoneSt = zoneStatust.val();
  //   if (table_number1.val().length === 0) {
  //     table_number1.addClass("invalid-mine");
  //   } else {
  //     let tNumber = table_number1.val();
  //     let tStatus = table_status1.val();
  //     $.ajax({
  //       url: "table_and_zone.php",
  //       method: "POST",
  //       dataType: "text",
  //       data: {
  //         addTable: 1,
  //         tableNumber: tNumber,
  //         tableStatus: tStatus,
  //         zoneNum: zoneSt,
  //       },
  //       success: function (e) {
  //         $("#reset_status-2").click();
  //         $(".add_table").hide();
  //         getAllTable(zoneSt);
  //         Swal.fire({
  //           position: "top-end",
  //           icon: "success",
  //           title: "เพิ่มโต๊ะสำเร็จ",
  //           showConfirmButton: false,
  //           timer: 1500,
  //         });
  //       },
  //     });
  //   }
  // });
  // table_number1.keyup(function () {
  //   if ($(this).val() != 0) {
  //     table_number1.removeClass("invalid-mine");
  //   }
  // });
  // $("#add_table_number").keypress(function (event) {
  //   var ew = event.which;
  //   if (48 <= ew && ew <= 57) return true;
  //   return false;
  // });

  getZoneAll();
  getAllTable();
});

function getZoneAll() {
  $.ajax({
    url: "table_and_zone.php",
    method: "POST",
    dataType: "text",
    data: {
      getZoneAll: 1,
    },
    success: function (e) {
      $(".get_zone_all").html(e);

      $(".btn-hover").click(function () {
        $(".btn-add-table").prop("disabled", false);

        $(this)
          .css({
            background: "#cee6ff",
          })
          .siblings()
          .css({
            background: "white",
          });

        var zoneClick = $.trim(
          $(this).children().children().find(".zone_number_5").text()
        );
        getAllTable(zoneClick);
      });

      $(".zone_edit_selected").change(function () {
        let statusZone = $(this).val();
        let zoneId = $(this).attr("data-id");
        if (statusZone == "แสดง" || statusZone == "ซ่อน") {
          $.ajax({
            url: "table_and_zone.php",
            method: "POST",
            dataType: "text",
            data: {
              editZone: 1,
              zone_id: zoneId,
              zone_status: statusZone,
            },
            success: function (e) {
              getZoneAll();
            },
          });
        }
      });
    },
  });
}

function getAllTable(zone = "null") {
  $.ajax({
    url: "table_and_zone.php",
    method: "POST",
    dataType: "text",
    data: {
      getAllTable: 1,
      zone: zone,
    },
    success: function (e) {
      $(".get_all_table").html(e);
      //แก้ไขโต๊ะ
      $(".table_edit_selected").change(function () {
        let table_id = $(this).attr("data-id");
        let table_status = $(this).val();
        $.ajax({
          url: "table_and_zone.php",
          method: "POST",
          dataType: "text",
          data: {
            editTable: 1,
            tableId: table_id,
            tableStatus: table_status,
          },
          success: function (e) {
            getAllTable(e);
          },
        });
      });
    },
  });
}

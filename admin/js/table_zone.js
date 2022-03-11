$(document).ready(function () {
  //เพิ่มโซน

  $(".btn-add-zone").click(function () {
    $(".form-add-zone").show();
    $(".btn-cancel-zone").click(function () {
      $(".form-add-zone").hide();
    });
  });

  let zoneNumber = $("#zone_number");
  let zoneStatus = $("#add_zone_status");
  $(".btn-save-zone").click(function () {
    if (zoneNumber.val().length === 0) {
      zoneNumber.addClass("invalid-mine");
    } else {
      let zoneNum = zoneNumber.val();
      let zoneS = zoneStatus.val();

      $.ajax({
        url: "table_and_zone.php",
        method: "POST",
        data: {
          addZone: 1,
          zoneNumber: zoneNum,
          zoneStatus: zoneS,
        },
        success: function (data) {
          zoneNumber.val("");
          $("#reset_status").click();
          $(".form-add-zone").hide();
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "บันทึกสำเร็จเรียบร้อยแล้ว",
            showConfirmButton: false,
            timer: 1500,
          });
          getZoneAll();
        },
      });
    }
  });
  zoneNumber.keypress(function (event) {
    var ew = event.which;

    if (48 <= ew && ew <= 57) return true;

    return false;
  });
  zoneNumber.keyup(function () {
    if ($(this).val().length !== 0) {
      zoneNumber.removeClass("invalid-mine");
    }
  });

  $(".btn-add-table").prop("disabled", true);

  $(".btn-add-table").click(function () {
    $(".add_table").show();
    $(".btn-cancel-table").click(function () {
      $(".add_table").hide();
    });
  });

  let table_number1 = $(".add_table_number");
  let table_status1 = $(".add_tatus_status");
  let zoneStatust = $(".add_table_zone");
  $(".btn-add-table-t").click(function () {
    let zoneSt = zoneStatust.val();
    if (table_number1.val().length === 0) {
      table_number1.addClass("invalid-mine");
    } else {
      let tNumber = table_number1.val();
      let tStatus = table_status1.val();
      $.ajax({
        url: "table_and_zone.php",
        method: "POST",
        dataType: "text",
        data: {
          addTable: 1,
          tableNumber: tNumber,
          tableStatus: tStatus,
          zoneNum: zoneSt,
        },
        success: function (e) {
          $("#reset_status-2").click();
          $(".add_table").hide();
          getAllTable(zoneSt);
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "เพิ่มโต๊ะสำเร็จ",
            showConfirmButton: false,
            timer: 1500,
          });
        },
      });
    }
  });
  table_number1.keyup(function () {
    if ($(this).val() != 0) {
      table_number1.removeClass("invalid-mine");
    }
  });
  $("#add_table_number").keypress(function (event) {
    var ew = event.which;
    if (48 <= ew && ew <= 57) return true;
    return false;
  });

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

      //ลบโซน
      $(".btn-delete-zone").click(function () {
        let zoneId = $(`.zone_id[data-id='${$(this).data("id")}']`);
        var zone_id = zoneId.val();
        Swal.fire({
          title: "คุณแน่ใจใช่หรือไม่?",
          text: "คุณต้องการลบโซนนี้จริงหรือไม่!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "ยืนยัน",
          cancelButtonText: "ยกเลิก",
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire("สำเร็จ!", "ทำการลบโซนนี้สำเร็จ", "success");
            $.ajax({
              url: "table_and_zone.php",
              method: "POST",
              dataType: "text",
              data: {
                deleteZone: 1,
                zoneId: zone_id,
              },
              success: function (e) {
                getZoneAll();
              },
            });
          }
        });
      });
      //แก้ไขโซน
      $(".btn-zone-edit-3").click(function () {
        let zoneId = $(this).val();
        $(".show-zone-1-" + zoneId).hide();
        $(".show-edit-zone-2-" + zoneId).show();
        $("#input_zone_number_edit_" + zoneId).keyup(function () {
          if ($(this).val() != "") {
            $("#input_zone_number_edit_" + zoneId).removeClass("invalid-mine");
          }
        });
      });

      //บักทึกการแก้ไข
      $(".btn-save-edit-zone").click(function () {
        let zoneId = $(this).val();
        let zoneNumOld = $.trim($("#zone_number_edit_old_" + zoneId).text());
        let zoneNumNew = $("#input_zone_number_edit_" + zoneId).val();
        let zoneStatusNew = $("#selet_zone_number_edit_" + zoneId).val();
        if (zoneNumNew == "") {
          $("#input_zone_number_edit_" + zoneId).addClass("invalid-mine");
        } else {
          $.ajax({
            url: "table_and_zone.php",
            method: "POST",
            dataType: "text",
            data: {
              editZone: 1,
              zoneId: zoneId,
              zoneNum: zoneNumNew,
              zoneOld: zoneNumOld,
              zoneStatus: zoneStatusNew,
            },
            success: function (e) {
              $("#reset_status-2").click();
              Swal.fire({
                position: "top-end",
                icon: "success",
                title: "บันทึกสำเร็จเรียบร้อยแล้ว",
                showConfirmButton: false,
                timer: 1500,
              });
              getZoneAll();
              getAllTable(e);
            },
          });
        }
      });

      //ยกเลิกการแก้ไขโซน
      $(".btn-cancel-edit-zone").click(function () {
        let zoneId = $(this).val();
        let zoneNumOld = $.trim($("#zone_number_edit_old_" + zoneId).text());
        let zoneStatusOld = $.trim($("#zone_status_edit_old_" + zoneId).text());
        $(".show-zone-1-" + zoneId).show();
        $(".show-edit-zone-2-" + zoneId).hide();
        $("#input_zone_number_edit_" + zoneId).val(zoneNumOld);
        $("#selet_zone_number_edit_" + zoneId).val(zoneStatusOld);
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
      $(".btn-delete-table-2").click(function () {
        let table_id = $(`.table_id[data-id='${$(this).data("id")}']`);
        var zoneNumm = $.trim(
          $(this).parent().parent().parent().find(".zone_number_c").text()
        );
        var tableIdd = table_id.val();
        // $.ajax({
        //   url: "table_and_zone.php",
        //   method: "POST",
        //   dataType: "text",
        //   data: {
        //     deleteTable: 1,
        //     tableId: tableIdd,
        //   },
        //   success: function (e) {
        //     getAllTable(zoneNumm);
        //   },
        // });

        Swal.fire({
          title: "คุณแน่ใจใช่หรือไม่?",
          text: "คุณต้องการลบโต๊ะนี้จริงหรือไม่!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "ยืนยัน",
          cancelButtonText: "ยกเลิก",
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire("สำเร็จ!", "ทำการลโต๊ะนี้สำเร็จ", "success");
            $.ajax({
              url: "table_and_zone.php",
              method: "POST",
              dataType: "text",
              data: {
                deleteTable: 1,
                tableId: tableIdd,
              },
              success: function (e) {
                getAllTable(zoneNumm);
              },
            });
          }
        });
      });

      //แก้ไขโต๊ะ
      $(".btn-edit-table-edit").click(function () {
        let tableId = $(this).val();
        $(".show_edit_table_1_" + tableId).hide();
        $(".show_edit_table_2_" + tableId).show();
        $("#input_table_edit_" + tableId).keyup(function () {
          if ($(this).val() != "") {
            $("#input_table_edit_" + tableId).removeClass("invalid-mine");
          }
        });
      });

      //บันทึกแก้ไขโต๊ะ
      $(".btn-save-table-edit").click(function () {
        let tableId = $(this).val();
        let inputNum = $("#input_table_edit_" + tableId).val();
        let tableStatus = $("#selet_talbe_edit_" + tableId).val();
        let talleZone = $("#selet_talbe_status_edit_" + tableId).val();
        if (inputNum == "") {
          $("#input_table_edit_" + tableId).addClass("invalid-mine");
        } else {
          $.ajax({
            url: "table_and_zone.php",
            method: "POST",
            dataType: "text",
            data: {
              editTable: 1,
              tableId: tableId,
              tableNumEdit: inputNum,
              tableStatusEdit: tableStatus,
              tableZoneEdit: talleZone,
            },
            success: function (e) {
              Swal.fire({
                position: "top-end",
                icon: "success",
                title: "บันทึกสำเร็จเรียบร้อยแล้ว",
                showConfirmButton: false,
                timer: 1500,
              });
              getZoneAll();
              getAllTable(talleZone);
            },
          });
        }
      });

      //ยกเลิกแก้ไขโต๊ะ
      $(".btn-cancel-table-edit").click(function () {
        let tableNum = $(this).val();
        let tableNumOld = $.trim($("#table_edit_old_" + tableNum).text());
        let zoneNumOld = $.trim($("#table_zone_number_" + tableNum).text());
        let statusNumOldd = $.trim($("#table_stust_edit_" + tableNum).text());
        $(".show_edit_table_1_" + tableNum).show();
        $(".show_edit_table_2_" + tableNum).hide();
        $("#input_table_edit_" + tableNum).val(tableNumOld);
        $("#selet_talbe_edit_" + tableNum).val(statusNumOldd);
        $("#selet_talbe_status_edit_" + tableNum).val(zoneNumOld);
      });
    },
  });
}

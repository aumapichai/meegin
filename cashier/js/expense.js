$(document).ready(function () {
  $("#pickyDate")
    .datepicker({
      format: "dd/mm/yyyy",
      todayBtn: "linked",
      language: "th",
    })
    .on("changeDate", showTestDate);

  function showTestDate() {
    let value = $("#pickyDate").datepicker("getFormattedDate");
    $("#show_txt_date_1").text("วันที่ " + value);
    $("#input_date_selected_2").val(value);
    getDataEx($("#pickyDate").datepicker("getFormattedDate"));
    getTotalEx($("#pickyDate").datepicker("getFormattedDate"));
  }

  $("#show_txt_date_1").text(
    "วันที่ " + $("#pickyDate").datepicker("getFormattedDate")
  );

  $("#input_date_selected_2").val(
    $("#pickyDate").datepicker("getFormattedDate")
  );
  $("#input_date_selected_2")
    .datepicker({
      format: "dd/mm/yyyy",
      todayBtn: "linked",
      language: "th",
    })
    .on("changeDate", showTestDate2);

  function showTestDate2() {
    let value = $("#input_date_selected_2").datepicker("getFormattedDate");
    $("#input_date_selected_2").val(value);
    $("#show_txt_date_1").text("วันที่ " + value);
    getDataEx(value);
    getTotalEx(value);
  }

  //date
  $("#expense_date").datepicker({
    format: "dd/mm/yyyy",
    todayBtn: "linked",
    language: "th",
  });

  //เพิ่มรายจ่าย
  let inputDate = $("#expense_date");
  let inputType = $("#expense_type");
  let inputAmount = $("#expense_amount");
  $(".btn-add-expense").click(function () {
    inputDate.val($("#pickyDate").datepicker("getFormattedDate"));
    inputDate.removeClass("invalid-mine");
    inputType.val("");
    inputType.removeClass("invalid-mine");
    inputAmount.val("");
    inputAmount.removeClass("invalid-mine");
    $("#myModalAddExpense").modal("show");
  });

  $(".btn-confirm-add-expense").click(function () {
    if (inputDate.val() == "") {
      inputDate.addClass("invalid-mine");
    } else if (inputType.val() == "") {
      inputType.addClass("invalid-mine");
    } else if (inputAmount.val() == "") {
      inputAmount.addClass("invalid-mine");
    } else {
      $.ajax({
        url: "expense.php",
        method: "POST",
        dataType: "text",
        data: {
          addExpense: 1,
          titleExpense: inputType.val(),
          amountExpense: inputAmount.val(),
          dateExpense: inputDate.val(),
        },
        success: function (e) {
          getDataEx($("#pickyDate").datepicker("getFormattedDate"));
          getTotalEx($("#pickyDate").datepicker("getFormattedDate"));
          $("#myModalAddExpense").modal("hide");
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "เพิ่มสำเร็จ",
            showConfirmButton: false,
            timer: 1500,
          });
        },
      });
    }
  });
  inputDate.on("keydown change", function () {
    let inputDateThis = $(this);
    setTimeout(() => {
      let inputDateVal = inputDateThis.val();
      if (inputDateVal != "") {
        inputDate.removeClass("invalid-mine");
      } else {
        inputDate.removeClass("invalid-mine");
      }
    }, 100);
  });

  inputType.on("change", function () {
    let inputTypeThis = $(this);
    setTimeout(() => {
      let inputTypeVal = inputTypeThis.val();
      if (inputTypeVal != "") {
        inputType.removeClass("invalid-mine");
      } else {
        inputType.removeClass("invalid-mine");
      }
    }, 100);
  });

  inputAmount.on("keydown change", function () {
    let inputAmountThis = $(this);
    setTimeout(() => {
      let inputAmountVal = inputAmountThis.val();
      if (inputAmountVal != "") {
        inputAmount.removeClass("invalid-mine");
      } else {
        inputAmount.removeClass("invalid-mine");
      }
    }, 100);
  });

  getDataEx($("#pickyDate").datepicker("getFormattedDate"));
  getTotalEx($("#pickyDate").datepicker("getFormattedDate"));
});

function getDataEx(date) {
  $.ajax({
    url: "expense.php",
    method: "POST",
    dataType: "text",
    data: {
      getAllDataEx: 1,
      dateAllEx: date,
    },
    success: function (e) {
      $("#show_data_expense").html(e);

      //ลบรายจ่าย
      $(".btn-delete-expense").click(function () {
        let id = $(this).val();
        Swal.fire({
          title: "ลบรายจ่าย!",
          text: "คุณต้องการลบรายจ่ายจริงหรือไม่?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "ยืนยัน",
          cancelButtonText: "ยกเลิก",
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "expense.php",
              method: "POST",
              dataType: "text",
              data: {
                deleteExpense: 1,
                deleteId: id,
              },
              success: function (e) {
                getDataEx($("#pickyDate").datepicker("getFormattedDate"));
                getTotalEx($("#pickyDate").datepicker("getFormattedDate"));
                Swal.fire({
                  position: "top-end",
                  icon: "success",
                  title: "ลบสำเร็จ",
                  showConfirmButton: false,
                  timer: 1500,
                });
              },
            });
          }
        });
      });

      //แก้ไขรายจ่าย
      $(".btn-edit-expense").click(function () {
        let id = $(this).val();
        $.ajax({
          url: "expense.php",
          method: "POST",
          dataType: "text",
          data: {
            findDataEx: 1,
            dataId: id,
          },
          success: function (e) {
            let split = e.split(",");
            let id = split[0];
            let title = split[1];
            let amount = split[2];
            let date = split[3];
            $("#myModalEditExpense").modal("show");

            //แก่ไชรายจ่าย
            let inputDate = $("#expense_date_edit");
            let inputType = $("#expense_type_edit");
            let inputAmount = $("#expense_amount_edit");
            inputDate.val(date);
            inputType.val(title);
            inputAmount.val(amount);
            inputDate.datepicker({
              format: "dd/mm/yyyy",
              todayBtn: "linked",
              language: "th",
            });
            $(".btn-confirm-add-expense-edit").click(function () {
              if (inputDate.val() == "") {
                inputDate.addClass("invalid-mine");
              } else if (inputType.val() == "") {
                inputType.addClass("invalid-mine");
              } else if (inputAmount.val() == "") {
                inputAmount.addClass("invalid-mine");
              } else {
                $.ajax({
                  url: "expense.php",
                  method: "POST",
                  dataType: "text",
                  data: {
                    editExpense: 1,
                    editIdExpense: id,
                    editTitleExpense: inputType.val(),
                    editAmountExpense: inputAmount.val(),
                    editDateExpense: inputDate.val(),
                  },
                  success: function (e) {
                    getDataEx($("#pickyDate").datepicker("getFormattedDate"));
                    getTotalEx($("#pickyDate").datepicker("getFormattedDate"));
                    $("#myModalEditExpense").modal("hide");
                    Swal.fire({
                      position: "top-end",
                      icon: "success",
                      title: "เพิ่มสำเร็จ",
                      showConfirmButton: false,
                      timer: 1500,
                    });
                  },
                });
              }
            });
            inputDate.on("keydown change", function () {
              let inputDateThis = $(this);
              setTimeout(() => {
                let inputDateVal = inputDateThis.val();
                if (inputDateVal != "") {
                  inputDate.removeClass("invalid-mine");
                } else {
                  inputDate.removeClass("invalid-mine");
                }
              }, 100);
            });

            inputType.on("change", function () {
              let inputTypeThis = $(this);
              setTimeout(() => {
                let inputTypeVal = inputTypeThis.val();
                if (inputTypeVal != "") {
                  inputType.removeClass("invalid-mine");
                } else {
                  inputType.removeClass("invalid-mine");
                }
              }, 100);
            });

            inputAmount.on("keydown change", function () {
              let inputAmountThis = $(this);
              setTimeout(() => {
                let inputAmountVal = inputAmountThis.val();
                if (inputAmountVal != "") {
                  inputAmount.removeClass("invalid-mine");
                } else {
                  inputAmount.removeClass("invalid-mine");
                }
              }, 100);
            });
          },
        });
      });
    },
  });
}

function getTotalEx(date) {
  $.ajax({
    url: "expense.php",
    method: "POST",
    dataType: "text",
    data: {
      getTotalExpense: 1,
      dateExpense: date,
    },
    success: function (e) {
      $(".number-amount-expense").html(e);
    },
  });
}

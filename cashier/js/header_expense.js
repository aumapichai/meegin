$(document).ready(function () {
  $("#pickyDate").datepicker({
    format: "dd/mm/yyyy",
    todayBtn: "linked",
    language: "th",
  });

  //เพิ่มหัวข้อ
  let inputTitleExpense = $("#title_expense");
  $(".btn-add-header-expense").on("click", function () {
    inputTitleExpense.val("");
    inputTitleExpense.removeClass("invalid-mine");
    $("#myModalAddExpense").modal("show");
  });

  $(".btn-confirm-add-expense").click(function () {
    if (inputTitleExpense.val() == "") {
      inputTitleExpense.addClass("invalid-mine");
    } else {
      $.ajax({
        url: "header_expense.php",
        method: "POST",
        dataType: "text",
        data: {
          addTitleExpense: 1,
          titleExpense: inputTitleExpense.val(),
        },
        success: function (e) {
          getAllExpenseData();
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
  inputTitleExpense.keyup(function () {
    if ($(this).val() != "") {
      inputTitleExpense.removeClass("invalid-mine");
    } else {
      inputTitleExpense.removeClass("invalid-mine");
    }
  });

  getAllExpenseData();
});

function getAllExpenseData() {
  $.ajax({
    url: "header_expense.php",
    method: "POST",
    dataType: "text",
    data: {
      getAllExpenseData: 1,
    },
    success: function (e) {
      $("#show_data_expense").html(e);

      //ลบหัวข้อ
      $(".btn-delete-title-expense").click(function () {
        let expenseId = $(this).val();
        Swal.fire({
          title: "ลบหัวข้อรายจ่าย!",
          text: "คุณต้องการลบจริงหรือไม่?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "ยืนยัน",
          cancelButtonText: "ยกเลิก",
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "header_expense.php",
              method: "POST",
              dataType: "text",
              data: {
                deleteTitleExpense: 1,
                expenseIdDelete: expenseId,
              },
              success: function (e) {
                getAllExpenseData();
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

      //แก้ไขหัวข้อ
      $(".btn-edit-title-expense").click(function () {
        let expenseIdEdit = $(this).val();
        let titleTxt = $("#title_expense_edit_" + expenseIdEdit);
        let inputTitleEdit = $("#edit_title_expense_" + expenseIdEdit);
        inputTitleEdit.show();
        inputTitleEdit.val($.trim(titleTxt.text()));
        titleTxt.hide();
        $(".show_expense_normal_" + expenseIdEdit).hide();
        $(".show_expense_edit_" + expenseIdEdit).show();
      });

      //ยกเลิกการแก้ไข
      $(".btn-cancel-edit-expense").click(function () {
        let id = $(this).val();
        let titleTxt = $("#title_expense_edit_" + id);
        let inputTitleEdit = $("#edit_title_expense_" + id);
        inputTitleEdit.hide();
        titleTxt.show();
        inputTitleEdit.removeClass("invalid-mine");
        $(".show_expense_normal_" + id).show();
        $(".show_expense_edit_" + id).hide();
      });

      //ยืนยันการแก้ไขหัวข้อ
      $(".btn-confirm-edit-expense").click(function () {
        let id = $(this).val();
        let inputTxtEx = $("#edit_title_expense_" + id);
        if (inputTxtEx.val() == "") {
          inputTxtEx.addClass("invalid-mine");
        } else {
          $.ajax({
            url: "header_expense.php",
            method: "POST",
            dataType: "text",
            data: {
              editTitleExpense: 1,
              expenseIdEditSend: id,
              expenseTitleSend: inputTxtEx.val(),
            },
            success: function (e) {
              getAllExpenseData();
              Swal.fire({
                position: "top-end",
                icon: "success",
                title: "แก้ไขสำเร็จ",
                showConfirmButton: false,
                timer: 1500,
              });
            },
          });
        }
        inputTxtEx.keyup(function () {
          if ($(this).val() != "") {
            inputTxtEx.removeClass("invalid-mine");
          } else {
            inputTxtEx.removeClass("invalid-mine");
          }
        });
      });
    },
  });
}

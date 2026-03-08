$(document).ready(function() {
  var MAX_CREDITS = 3;

  // إضافة صف جديد
  $('#addCourse').click(function() {
    var row = $('.course-row').first().clone();
    row.find('input').val('');
    row.append(
      '<div class="col-auto">' +
        '<button type="button" class="btn btn-danger remove-row">X</button>' +
      '</div>'
    );
    $('#courses').append(row);
  });

  // إزالة صف
  $(document).on('click', '.remove-row', function() {
    if ($('.course-row').length > 1) {
      $(this).closest('.course-row').remove();
    }
  });

  // إرسال النموذج عبر AJAX
  $('#gpaForm').submit(function(e) {
    e.preventDefault();

    var valid = true;
    var courseNames = [];

    // التحقق من أسماء المقررات
    $('[name="course[]"]').each(function() {
      var courseName = $(this).val().trim();
      if (courseName === '') valid = false;

      if (courseNames.includes(courseName.toLowerCase())) {
        valid = false;
        $('#result').html('<div class="alert alert-warning">Duplicate course names are not allowed.</div>');
        return false; // يوقف الحلقة
      }
      courseNames.push(courseName.toLowerCase());
    });

    // التحقق من الساعات
    $('[name="credits[]"]').each(function() {
      var cr = Number($(this).val()); // تحويل إلى رقم
      if (isNaN(cr) || cr <= 0 || cr > MAX_CREDITS) {
        valid = false;
        $('#result').html('<div class="alert alert-warning">Credits must be between 1 and ' + MAX_CREDITS + '.</div>');
        return false; // يوقف الحلقة
      }
    });

    if (!valid) return; // إذا كان هناك خطأ لا يُرسل النموذج

    // إرسال البيانات إلى calculate.php
    $.ajax({
      url: 'calculate.php',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          var alertClass = 'alert-info';
          if (response.gpa >= 3.7) alertClass = 'alert-success';
          else if (response.gpa >= 3.0) alertClass = 'alert-info';
          else if (response.gpa >= 2.0) alertClass = 'alert-warning';
          else alertClass = 'alert-danger';

          $('#result').html(
            '<div class="alert ' + alertClass + '">' +
              response.message +
            '</div>' +
            response.tableHtml
          );

          var percent = (response.gpa / 4.0) * 100;
          var barClass = 'bg-info';
          if (response.gpa >= 3.7) barClass = 'bg-success';
          else if (response.gpa >= 3.0) barClass = 'bg-info';
          else if (response.gpa >= 2.0) barClass = 'bg-warning';
          else barClass = 'bg-danger';

          $('#gpaBar')
            .css('width', percent + '%')
            .removeClass('bg-success bg-info bg-warning bg-danger')
            .addClass(barClass)
            .text(response.gpa.toFixed(2));
        } else {
          $('#result').html('<div class="alert alert-danger">' + response.message + '</div>');
        }
      },
      error: function() {
        $('#result').html('<div class="alert alert-danger">Server error occurred.</div>');
      }
    });
  });
});

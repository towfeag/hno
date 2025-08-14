// Show section logic for admin panel
function showSection(section) {
  $('.admin-section').hide();
  $('#' + section + '-section').show();
  if (section === 'users') {
    $('#example').DataTable().columns.adjust().draw();
  }
  if (section === 'faculties') {
    $('#facultyTable').DataTable().columns.adjust().draw();
  }
}
// Admin Panel JS
$(document).ready(function () {
  // Unified DataTable init for users
  var table = $('#example').DataTable({
    processing: true,
    serverSide: true,
    autoWidth: false,
    responsive: true,
    ajax: {
      url: 'fetch_data.php',
      type: 'POST'
    },
    columnDefs: [
      { orderable: false, targets: [5] },
      { className: 'dt-center align-middle', targets: '_all' }
    ],
    language: {
      processing:     "جاري المعالجة...",
      search:         "بحث:",
      lengthMenu:     "أظهر _MENU_ مدخلات",
      info:           "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخلة",
      infoEmpty:      "يعرض 0 إلى 0 من أصل 0 مدخلة",
      infoFiltered:   "(منتقاة من مجموع _MAX_ مدخلة)",
      loadingRecords: "جارٍ التحميل...",
      zeroRecords:    "لم يتم العثور على سجلات مطابقة",
      emptyTable:     "لا توجد بيانات متاحة في الجدول",
      paginate: {
        first:      "الأول",
        previous:   "السابق",
        next:       "التالي",
        last:       "الأخير"
      },
      aria: {
        sortAscending:  ": تفعيل لترتيب العمود تصاعدياً",
        sortDescending: ": تفعيل لترتيب العمود تنازلياً"
      }
    },
    drawCallback: function() {
      // Add custom class to table for extra spacing
      $('#example').addClass('table-aligned');
    }
  });

  // Faculty Table (for admin faculty management)
  var facultyTable = $('#facultyTable').DataTable({
    data: [],
    autoWidth: false,
    responsive: true,
    columnDefs: [
      { orderable: false, targets: [4] },
      { className: 'align-middle text-center', targets: [0,4] }, // ID and actions centered
      { className: 'align-middle text-start text-truncate', targets: [1,2,3] } // name, desc, link left/truncate
    ],
    language: table.settings()[0].oLanguage,
    drawCallback: function() {
      $('#facultyTable').addClass('table-aligned');
      // Add Bootstrap tooltip for truncated text
      $('#facultyTable td.text-truncate').each(function() {
        if (this.offsetWidth < this.scrollWidth) {
          $(this).attr('title', $(this).text());
        } else {
          $(this).removeAttr('title');
        }
      });
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('#facultyTable [title]'));
      tooltipTriggerList.forEach(function (el) {
        new bootstrap.Tooltip(el);
      });
    }
  });
  // Add user
  $('#addUserForm').on('submit', function (e) {
    e.preventDefault();
    $.post('add_user.php', $(this).serialize(), function (response) {
      try {
        let res = JSON.parse(response);
        console.log('Raw response:', response);
        console.log('Parsed JSON:', res);
        if (res.status === 'true') {
          $('#addUserModal').modal('hide');
          $('#addUserForm')[0].reset();
          table.ajax.reload();
          showAlert('تمت إضافة المستخدم بنجاح! كلمة المرور الافتراضية: 123', 'success');
        } else {
          showAlert(res.message || 'فشل في إضافة المستخدم.', 'danger');
        }
      } catch (error) {
        console.error('Invalid JSON:', response);
        showAlert('خطأ في الاتصال بالخادم. حاول مرة أخرى.', 'danger');
      }
    });
  });

  // Add faculty (send correct field names)
  $('#addFacultyForm').on('submit', function (e) {
  e.preventDefault();
  $.post('add_faculty.php', $(this).serialize(), function (response) {
    try {
      let res = JSON.parse(response);
      console.log('Raw response:', response);
      console.log('Parsed JSON:', res);
      if (res.status === 'true') {
        $('#addFacultyModal').modal('hide');
        $('#addFacultyForm')[0].reset();
        reloadFacultyTable();
        showAlert('تمت إضافة الكلية بنجاح', 'success');
      } else {
        showAlert(res.message || 'فشل في إضافة الكلية.', 'danger');
      }
    } catch (error) {
      console.error('Invalid JSON:', response);
      showAlert('خطأ في الاتصال بالخادم. حاول مرة أخرى.', 'danger');
      }
    });
  });


  // Edit user button click
  $('#example').on('click', '.editbtn', function () {
    const data = table.row($(this).closest('tr')).data();
    $('#edit-id').val(data[0]);
    $('#edit-fullname').val(data[1]);
    $('#edit-username').val(data[2]);
    $('#edit-email').val(data[3]);
    $('#edit-role').val(data[4]);
    $('#editUserModal').modal('show');
  });

  // Edit faculty button click
  $('#facultyTable').on('click', '.editFacultyBtn', function () {
    alert('ddddd');
    const data = facultyTable.row($(this).closest('tr')).data();
    // Extract plain text from HTML for name and description, and href for link
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = data[1];
    const nameText = tempDiv.textContent || tempDiv.innerText || '';
    tempDiv.innerHTML = data[2];
    const descText = tempDiv.textContent || tempDiv.innerText || '';
    tempDiv.innerHTML = data[3];
    let linkText = '';
    const aTag = tempDiv.querySelector('a');
    if (aTag) {
      linkText = aTag.getAttribute('href') || '';
    } else {
      linkText = tempDiv.textContent || tempDiv.innerText || '';
    }
    $('#edit-faculty-id').val(data[0]);
    $('#edit-faculty-name').val(nameText);
    $('#edit-faculty-desc').val(descText);
    $('#edit-faculty-link').val(linkText);
    $('#editFacultyModal').modal('show');
  });

  // Update user
  $('#updateUserForm').on('submit', function (e) {
    e.preventDefault();
    $.post('update_user.php', $(this).serialize(), function () {
      $('#editUserModal').modal('hide');
      table.ajax.reload();
      showAlert('تم تحديث المستخدم بنجاح!');
    });
  });

  // Update faculty (send correct field names)
  $('#editFacultyForm').on('submit', function (e) {
  e.preventDefault();
  alert('Submitting...'); // Test
  $.post('update_faculty.php', $(this).serialize(), function (res) {
    console.log('Raw response:', res);
    let json;
    try {
      json = JSON.parse(res);
    } catch (err) {
      showAlert('خطأ في استجابة الخادم.', 'danger');
      return;
    }
    if (json.status === 'true') {
      $('#editFacultyModal').modal('hide');
      reloadFacultyTable();
      showAlert(json.message || 'تم تحديث الكلية بنجاح', 'success');
    } else {
      showAlert(json.message || 'فشل في تحديث الكلية.', 'danger');
    }
  });
 });



  // Open delete user modal
  $('#example').on('click', '.deleteBtn', function () {
    const id = $(this).data('id');
    $('#delete-id').val(id);
    $('#deleteUserModal').modal('show');
  });

  // Open delete faculty modal
  $('#facultyTable').on('click', '.deleteFacultyBtn', function () {
    const id = $(this).data('id');
    $('#delete-faculty-id').val(id);
    $('#deleteFacultyModal').modal('show');
  });

  // Confirm delete user
  $('#deleteUserForm').on('submit', function (e) {
    e.preventDefault();
    const id = $('#delete-id').val();
    $.post('delete_user.php', { id }, function (res) {
      const json = JSON.parse(res);
      $('#deleteUserModal').modal('hide');
      if (json.status === 'success') {
        table.ajax.reload();
        showAlert('تم حذف المستخدم بنجاح!');
      } else {
        showAlert('فشل في حذف المستخدم.', 'danger');
      }
    });
  });

  // Confirm delete faculty
  $('#deleteFacultyForm').on('submit', function (e) {
    e.preventDefault();
    const id = $('#delete-faculty-id').val();
    $.post('delete_faculty.php', { id }, function (res) {
      const json = JSON.parse(res);
      $('#deleteFacultyModal').modal('hide');
      if (json.status === 'success') {
        showAlert('تم حذف الكلية بنجاح!');
        window.scrollTo({ top: 0, behavior: 'smooth' });
        setTimeout(function() {
          facultyTable.ajax ? facultyTable.ajax.reload() : reloadFacultyTable();
        }, 600); // Wait for alert to show before reload
      } else {
        showAlert('فشل في حذف الكلية.', 'danger');
      }
    });
  });

  // Faculty management: load faculties into table (AJAX, no DB fallback)
  function reloadFacultyTable() {
    $.get('get_faculties.php', function (res) {
      // alert('Raw faculties data: ' + res);
      let faculties = Array.isArray(res) ? res : JSON.parse(res);
      facultyTable.clear();
      faculties.forEach(function (f, i) {
        facultyTable.row.add([
          f.id || (i+1),
          `<span class='text-truncate d-inline-block' style='max-width: 140px;' title='${f.name}'>${f.name}</span>`,
          `<span class='text-truncate d-inline-block' style='max-width: 180px;' title='${f.description}'>${f.description}</span>`,
          `<a href="${f.link}" target="_blank" class='text-truncate d-inline-block' style='max-width: 160px;' title='${f.link}'>${f.link}</a>`,
          `<button class="btn btn-sm btn-primary editFacultyBtn" data-id="${f.id}">تعديل</button> <button class="btn btn-sm btn-danger deleteFacultyBtn" data-id="${f.id}">حذف</button>`
        ]);
      });
      facultyTable.draw();
    });
  }
  reloadFacultyTable();
});

function showAlert(message, type = 'success', duration = 3500) {
  const wrapper = document.createElement('div');
  wrapper.innerHTML = `
    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`;
  document.getElementById('alertPlaceholder').append(wrapper);
  setTimeout(() => {
    if (wrapper.parentNode) wrapper.remove();
  }, duration);
}

function confirmlogout(e) {
  e.preventDefault();
  const confirmed = confirm("هل أنت متأكد أنك تريد تسجيل الخروج؟");
  if (confirmed) {
    window.location.href = "?logout=true";
  }
}

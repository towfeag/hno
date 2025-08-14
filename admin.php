<?php
session_start();

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location:index.html");
    exit();
}
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
?>
<?php include('conn.php'); ?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>المستخدمين (أعضاء هيئة التدريس والطلاب)</title>

  <!-- Bootstrap 5 CSS -->
  <link href="css/bootstrap5.0.1.min.css" rel="stylesheet" crossorigin="anonymous">
  <link href="css/datatables-1.10.25.min.css" rel="stylesheet">
  <script src="js/jquery-3.6.0.min.js"></script> 
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/dt-1.10.25datatables.min.js"></script>
  <script src="admin.js"></script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@500&display=swap');
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: 'Cairo', sans-serif !important;
      background: linear-gradient(120deg, #003366 0%, #336699 100%);
      min-height: 100vh;
      color: #f8fafc;
    }
    .bg-light {
      background: #f8fafc !important;
      border-radius: 0 20px 20px 0;
      box-shadow: 2px 0 10px rgba(0,0,0,0.04);
    }
    .nav-link {
      color: #1c4a8f;
      font-weight: 600;
      border-radius: 8px;
      transition: background 0.2s, color 0.2s;
    }
    .nav-link.active, .nav-link:hover {
      background: #1c4a8f;
      color: #fff !important;
    }
    .card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 2px 12px rgba(44,62,80,0.08);
      background: #f8fafc;
    }
    .card-title {
      color: #003366;
      font-weight: bold;
    }
    .badge {
      font-size: 1.3rem;
      padding: 0.7em 1.2em;
      border-radius: 12px;
    }
    .admin-section {
      animation: fadeIn 0.5s;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: none; }
    }
    .table {
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(44,62,80,0.06);
    }
    .table th {
      background: #eaf1fb;
      color: #003366;
      font-weight: 700;
      border: none;
    }
    .table td {
      vertical-align: middle;
      border: none;
    }
    .btn-primary, .btn-success, .btn-danger, .btn-secondary {
      border-radius: 8px;
      font-weight: 600;
      padding: 0.4em 1.2em;
      font-size: 1em;
    }
    .btn-primary {
      background: #1c4a8f;
      border: none;
    }
    .btn-success {
      background: #2ecc71;
      border: none;
    }
    .btn-danger {
      background: #e74c3c;
      border: none;
    }
    .btn-secondary {
      background: #95a5a6;
      border: none;
    }
    .modal-content {
      border-radius: 16px;
      box-shadow: 0 2px 16px rgba(44,62,80,0.10);
    }
    .form-label {
      color: #1c4a8f;
      font-weight: 600;
    }
    .form-control, .form-select {
      border-radius: 8px;
      border: 1px solid #eaf1fb;
      background: #f8fafc;
      color: #003366;
    }
    .form-control:focus, .form-select:focus {
      border-color: #1c4a8f;
      box-shadow: 0 0 0 2px #cce0ff;
    }
    .modal-header {
      border-bottom: none;
      color: #000000;
      flex-direction: column-reverse;  
    }
    .modal-footer {
      border-top: none;
    }
    .alert {
      border-radius: 12px;
      font-size: 1.1em;
      box-shadow: 0 2px 8px rgba(44,62,80,0.08);
    }
    @media (max-width: 900px) {
      .d-flex { flex-direction: column; }
      nav.bg-light { width: 100%; min-height: unset; border-radius: 0 0 20px 20px; }
      .flex-grow-1 { padding: 1rem !important; }
    }
  </style>
</head>

<div class="d-flex" style="min-height:100vh;">
  <!-- Sidebar -->
  <nav class="bg-light border-end p-3" style="width:240px;min-height:100vh;">
    <div class="mb-4 text-center">
      <img src="upload/image/logo.jpg" alt="logo" style="height:60px;border-radius:8px;box-shadow:0 2px 8px rgba(44,62,80,0.10);">
      <h5 class="mt-2 mb-0" style="color:#1c4a8f;font-weight:bold;">لوحة الإدارة</h5>
    </div>
    <ul class="nav flex-column">
      <li class="nav-item mb-2"><a class="nav-link active" href="#dashboard" onclick="showSection('dashboard')">لوحة التحكم</a></li>
      <li class="nav-item mb-2"><a class="nav-link" href="#users" onclick="showSection('users')">إدارة المستخدمين</a></li>
      <li class="nav-item mb-2"><a class="nav-link" href="#faculties" onclick="showSection('faculties')">إدارة الكليات</a></li>
      <li class="nav-item mb-2"><a class="nav-link text-danger" href="#" onclick="confirmlogout(event)">تسجيل الخروج</a></li>
    </ul>
  </nav>

  <!-- Main Content -->
  <div class="flex-grow-1 p-4">
    <div id="alertPlaceholder" class="position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 9999;"></div>

    <!-- Dashboard Section -->
    <div id="dashboard-section" class="admin-section">
      <h2 class="mb-4">لوحة التحكم</h2>
      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <h5 class="card-title">عدد أعضاء هيئة التدريس</h5>
              <span id="facultyUserCount" class="badge bg-primary fs-4">--</span>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <h5 class="card-title">عدد الطلاب</h5>
              <span id="studentUserCount" class="badge bg-info fs-4">--</span>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <h5 class="card-title">عدد الكليات</h5>
              <span id="facultyCount" class="badge bg-success fs-4">--</span>
            </div>
          </div>
        </div>
      </div>
      <div class="text-muted">مرحباً بك في لوحة تحكم الإدارة. استخدم القائمة الجانبية لإدارة المستخدمين والكليات.</div>
    </div>

    <!-- Users Section -->
    <div id="users-section" class="admin-section" style="display:none;">
      <div class="header-container text-center mb-3">
        <h2>المستخدمين (أعضاء هيئة التدريس والطلاب)</h2>
        <div class="btnAdd mb-3">
          <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">إضافة مستخدم</a>
        </div>
      </div>
      <table id="example" class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>المعرف</th>
            <th>الاسم الكامل</th>
            <th>اسم المستخدم</th>
            <th>البريد الإلكتروني</th>
            <th>الدور</th>
            <th>إجراء</th>
          </tr>
        </thead>
      </table>
    </div>

    <!-- Faculties Section (Admin Feature) -->
    <div id="faculties-section" class="admin-section" style="display:none;">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 >إدارة الكليات</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFacultyModal">إضافة كلية</button>
      </div>
      <table class="table table-bordered" id="facultyTable">
        <thead>
          <tr>
            <th>المعرف</th>
            <th>اسم الكلية</th>
            <th>الوصف</th>
            <th>الرابط</th>
            <th>الإجراء</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Faculty Modal -->
<div class="modal fade" id="addFacultyModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="addFacultyForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">إضافة كلية</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">اسم الكلية</label>
            <input type="text" class="form-control" name="name" id="faculty_name">
          </div>
          <div class="mb-3">
            <label class="form-label">الوصف</label>
            <textarea class="form-control" name="description" id="faculty_desc"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">رابط الصفحة</label>
            <input type="text" class="form-control" name="link" id="faculty_link">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">إضافة</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Faculty Modal -->
<div class="modal fade" id="editFacultyModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="editFacultyForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">تعديل الكلية</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit-faculty-id">
          <div class="mb-3">
            <label class="form-label">اسم الكلية</label>
            <input type="text" class="form-control" name="name" id="edit-faculty-name">
          </div>
          <div class="mb-3">
            <label class="form-label">الوصف</label>
            <textarea class="form-control" name="description" id="edit-faculty-desc"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">رابط الصفحة</label>
            <input type="text" class="form-control" name="link" id="edit-faculty-link">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">تحديث</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Delete Faculty Modal -->
<div class="modal fade" id="deleteFacultyModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="deleteFacultyForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">تأكيد حذف الكلية</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>هل أنت متأكد أنك تريد حذف هذه الكلية؟</p>
          <input type="hidden" id="delete-faculty-id" name="id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-danger">حذف</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="addUserForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">إضافة مستخدم</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">الاسم الكامل</label>
            <input type="text" class="form-control" name="fullname" id="add-fullname">
          </div>
          <div class="mb-3">
            <label class="form-label">اسم المستخدم</label>
            <input type="text" class="form-control" name="username" id="add-username">
          </div>
          <div class="mb-3">
            <label class="form-label">البريد الإلكتروني</label>
            <input type="email" class="form-control" name="email" id="add-email">
          </div>
          <div class="mb-3">
            <label class="form-label">الدور</label>
            <select class="form-select" name="role" id="add-role">
              <option value="faculty">عضو هيئة تدريس</option>
              <option value="student">طالب</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">إضافة المستخدم</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="updateUserForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">تعديل المستخدم</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit-id">
          <div class="mb-3">
            <label class="form-label">الاسم الكامل</label>
            <input type="text" class="form-control" name="fullname" id="edit-fullname">
          </div>
          <div class="mb-3">
            <label class="form-label">اسم المستخدم</label>
            <input type="text" class="form-control" name="username" id="edit-username">
          </div>
          <div class="mb-3">
            <label class="form-label">البريد الإلكتروني</label>
            <input type="email" class="form-control" name="email" id="edit-email">
          </div>
          <div class="mb-3">
            <label class="form-label">الدور</label>
            <select class="form-select" name="role" id="edit-role">
              <option value="faculty">عضو هيئة تدريس</option>
              <option value="student">طالب</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">تحديث</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="deleteUserForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">تأكيد الحذف</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>هل أنت متأكد أنك تريد حذف هذا المستخدم؟</p>
          <input type="hidden" id="delete-id" name="id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-danger">حذف</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script>
// Dashboard: fetch and update faculty/student user counts and faculty count
$(document).ready(function() {
  function updateDashboardCounts() {
    // Get faculty and student user counts
    $.get('fetch_user_roles_count.php')
      .done(function(res) {
        console.log('User roles count response:', res);
        let facultyCount = '--', studentCount = '--';
        try {
          if (typeof res === 'string') res = JSON.parse(res);
          facultyCount = res.faculty;
          studentCount = res.student;
        } catch (e) {
          console.error('User roles count JSON parse error:', e, res);
          facultyCount = studentCount = 'خطأ';
        }
        $('#facultyUserCount').text(facultyCount);
        $('#studentUserCount').text(studentCount);
      })
      .fail(function(xhr, status, error) {
        console.error('User roles count AJAX error:', status, error, xhr.responseText);
        $('#facultyUserCount').text('خطأ');
        $('#studentUserCount').text('خطأ');
      });
    // Get faculty (college) count
    $.get('fetch_faculty_count.php')
      .done(function(res) {
        console.log('Faculty count response:', res);
        let count = '--';
        try {
          if (typeof res === 'string') res = JSON.parse(res);
          count = res.count;
        } catch (e) {
          console.error('Faculty count JSON parse error:', e, res);
          count = 'خطأ';
        }
        $('#facultyCount').text(count);
      })
      .fail(function(xhr, status, error) {
        console.error('Faculty count AJAX error:', status, error, xhr.responseText);
        $('#facultyCount').text('خطأ');
      });
  }
  updateDashboardCounts();
});
</script>

</body>
</html>
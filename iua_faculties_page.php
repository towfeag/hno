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
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>كليات جامعة إفريقيا العالمية</title>
  <link rel="stylesheet" href="style.css">
  <link href="css/bootstrap5.0.1.min.css" rel="stylesheet">
</head>
<body class="main">

  <!-- إضافة شعار الجامعة -->
  <div style="display:flex; flex-direction: row-reverse;align-items: center;justify-content: center;gap: 20px;margin-bottom: 30px;">
    <img src="upload/image/logo.jpg" alt="logo" style="height: 80px; border-radius: 12px; box-shadow: 0 2px 8px rgba(44,62,80,0.13);">
    <div style="text-align: center;"></div>
  </div>

  <div class="container">
    <h1>كليات جامعة إفريقيا العالمية</h1>

    <div class="search-box" style="text-align:center; margin-bottom:18px;">
      <input type="text" id="faculty-search" placeholder="ابحث عن كلية..." style="width:60%;padding:10px;border-radius:6px;border:1px solid #ccc;font-size:1.05rem;">
    </div>
    <div class="card-container" id="faculties-list"></div>
    <script src="faculties-data.js"></script>
    <script>
      function renderFaculties(filter = "") {
        const container = document.getElementById('faculties-list');
        container.innerHTML = "";
        facultiesData.filter(fac => fac.name.includes(filter)).forEach(fac => {
          const card = document.createElement('div');
          card.className = 'card';
          card.tabIndex = 0;
          card.setAttribute('aria-label', fac.name);
          card.innerHTML = `
            <a href="${fac.link}">
              <img src="${fac.image}" alt="${fac.name}" style="height:60px;display:block;margin:0 auto 12px auto;border-radius:8px;box-shadow:0 2px 8px rgba(44,62,80,0.10);">
              <div style="font-size:1.1rem;font-weight:600;">${fac.name}</div>
              <div style="font-size:0.98rem;color:#275595;margin-top:8px;">${fac.highlight}</div>
            </a>
          `;
          container.appendChild(card);
        });
      }
      document.getElementById('faculty-search').addEventListener('input', function() {
        renderFaculties(this.value.trim());
      });
      renderFaculties();
    </script>

    <!-- زر تسجيل الخروج -->
    <div class="logout">
  <a href="#" onclick="confirmlogout(event)">تسجيل الخروج</a>
</div>

<script>
  function confirmlogout(e) {
    e.preventDefault(); // Prevent the default link action
    const confirmed = confirm("هل أنت متأكد أنك تريد تسجيل الخروج؟");
    if (confirmed) {
      // Redirect to logout
      window.location.href = "?logout=true";
    }
  }
</script>
</body>
</html>

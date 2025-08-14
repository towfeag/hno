<?php
include('conn.php');

// Prepare base SQL query to get only 'faculty' and 'student' roles
$sql = "SELECT * FROM users WHERE role IN ('faculty', 'student')";

// Get total records with role filter (no search)
$totalQuery = mysqli_query($conn, "SELECT * FROM users WHERE role IN ('faculty', 'student')");
$total_all_rows = mysqli_num_rows($totalQuery);

// Define columns used in sorting
$columns = array(
    0 => 'id',
    1 => 'fullname',
    2 => 'username',
    3 => 'email',
    4 => 'role'
);

// Search filter (if applicable)
if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " AND (username LIKE '%" . $search_value . "%' 
              OR email LIKE '%" . $search_value . "%' 
              OR fullname LIKE '%" . $search_value . "%')";
}

// Order/sorting
if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $columns[$column_name] . " " . $order;
} else {
    $sql .= " ORDER BY id DESC";
}

// Pagination
if ($_POST['length'] != -1) {
    $start = $_POST['start'];
    $length = $_POST['length'];
    $sql .= " LIMIT " . $start . ", " . $length;
}

$query = mysqli_query($conn, $sql);
$count_rows = mysqli_num_rows($query);

// Prepare data array
$data = array();
while ($row = mysqli_fetch_assoc($query)) {
    $sub_array = array();
    $sub_array[] = $row['id'];
    $sub_array[] = $row['fullname'];
    $sub_array[] = $row['username'];
    $sub_array[] = $row['email'];
    $sub_array[] = $row['role'];
    $sub_array[] = '<a href="javascript:void();" data-id="' . $row['id'] . '" class="btn btn-secondary btn-sm editbtn">تعديل</a>  
                    <a href="javascript:void();" data-id="' . $row['id'] . '" class="btn btn-danger btn-sm deleteBtn">حذف</a>';
    $data[] = $sub_array;
}

// Prepare final output
$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $total_all_rows,
    'recordsFiltered' => $count_rows,
    'data' => $data
);

// Return as JSON
header('Content-Type: application/json');
echo json_encode($output);
exit;

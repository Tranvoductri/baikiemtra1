<!DOCTYPE html>
<html>
<head>
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            color: black;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <?php
    // Thông tin kết nối đến cơ sở dữ liệu
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "quanlynhansu";

    // Số nhân viên hiển thị trên mỗi trang
    $employeesPerPage = 5;

    // Trang hiện tại
    $currentpage = isset($_GET['page']) ? $_GET['page'] : 1;

    // Tạo kết nối
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Truy vấn dữ liệu
    $sql = "SELECT nhanvien.MaNV, nhanvien.TenNV, nhanvien.Phai, nhanvien.Noi_Sinh, phongban.Ma_Phong, nhanvien.Luong 
            FROM nhanvien
            INNER JOIN phongban ON nhanvien.Ma_Phong = phongban.Ma_Phong";

    $result = $conn->query($sql);

    // Tổng số nhân viên
    $totalEmployees = $result->num_rows;

    // Tính toán số trang
    $totalPages = ceil($totalEmployees / $employeesPerPage);

    // Xác định phạm vi dữ liệu trên trang hiện tại
    $start = ($currentpage - 1) * $employeesPerPage;
    $end = $start + $employeesPerPage;

    // Truy vấn dữ liệu trang hiện tại với phạm vi đã xác định
    $sql .= " LIMIT $start, $employeesPerPage";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Hiển thị dữ liệu
        echo "<table>
                <tr>
                    <th>MaNV</th>
                    <th>Ten_NV</th>
                    <th>Phái</th>
                    <th>Nơi_Sinh</th>
                    <th>Tên_Phòng</th>
                    <th>Lương</th>
                </tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>".$row["MaNV"]."</td>
                    <td>".$row["TenNV"]."</td>
                    <td>";
            if($row["Phai"] == "NAM") {
                echo "<img src='nam.jpg' alt='NAM'>";
            } else {
                echo "<img src='nu.jpg' alt='NU'>";
            }
            echo "</td>
                    <td>".$row["Noi_Sinh"]."</td>
                    <td>".$row["Ma_Phong"]."</td>
                    <td>".$row["Luong"]."</td>
                </tr>";
        }
        echo "</table>";

        // Hiển thị phân trang
        echo "<div class='pagination'>";
        if ($currentpage > 1) {
            echo "<a href='?page=".($currentpage - 1)."'>Trang trước</a> ";
        }

        for ($i= 1; $i <= $totalPages; $i++) {
            if ($i == $currentpage) {
                echo "<a class='active' href='?page=$i'>$i</a> ";
            } else {
                echo "<a href='?page=$i'>$i</a> ";
            }
        }

        if ($currentpage < $totalPages) {
            echo "<a href='?page=".($currentpage + 1)."'>Trang sau</a> ";
        }
        echo "</div>";
    } else {
        echo "Không có nhân viên nào";
    }

    $conn->close();
    ?>
</body>
</html>
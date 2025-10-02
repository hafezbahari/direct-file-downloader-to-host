<?php
// اگر کاربر آدرس و نام فایل رو ارسال کرد
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    set_time_limit(0);

    // گرفتن ورودی‌ها
    $download_url = trim($_POST['file_url']);
    $file_name    = trim($_POST['file_name']);

    $save_path = "downloads/";
    $destination_file = $save_path . $file_name;

    if (!file_exists($save_path)) {
        mkdir($save_path, 0777, true);
    }

    // تابع دانلود با cURL
    function downloadFile($url, $path) {
        $fp = fopen($path, 'w+');
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_BUFFERSIZE, 128 * 1024);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)",
            "Accept: */*"
        ]);

        $result = curl_exec($ch);

        if ($result === false) {
            echo "<div class='alert alert-danger mt-3'>❌ خطا در دانلود: " . curl_error($ch) . "</div>";
        } else {
            echo "<div class='alert alert-success mt-3'>✅ دانلود موفق:  <a href='{$path}' target='_blank'>" . basename($path) . "</a></div>";
        }

        curl_close($ch);
        fclose($fp);
    }

    downloadFile($download_url, $destination_file);
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دانلود مستقیم فایل روی هاست</title>

    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <!-- فونت وزیر از فونت ایران -->
    <link rel="stylesheet" href="https://cdn.fontcdn.ir/Font/Vazir/Vazir.css">

    <style>
        body {
            font-family: 'Vazir', sans-serif;
            background-color: #f8f9fa;
        }
        .card-header {
            font-size: 1.1rem;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">دانلود مستقیم فایل روی هاست</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="" class="row g-3">
                <div class="col-12">
                    <label for="file_url" class="form-label">آدرس فایل (URL)</label>
                    <input type="url" name="file_url" id="file_url" class="form-control" placeholder="مثال: https://wordpress.org/latest.zip" required>
                </div>
                <div class="col-12">
                    <label for="file_name" class="form-label">نام ذخیره‌سازی فایل</label>
                    <input type="text" name="file_name" id="file_name" class="form-control" placeholder="مثال: wordpress.zip" required>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success px-4">دانلود</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>

<?php
$url = 'https://vnexpress.net/the-thao';

// Lấy nội dung trang bằng cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$content = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($content === false || $httpCode != 200) {
    die("Không tải được trang: $url (HTTP Code: $httpCode)");
}

// In ra HTML tải về (1000 ký tự đầu)
echo "<h3>HTML tải về (1000 ký tự đầu):</h3>";
echo "<pre>" . htmlspecialchars(substr($content,0,1000)) . "</pre>";

// Lọc các thẻ <h3 class="title-news__title">…</h3>
$pattern = '/<h3[^>]*class=["\']title-news__title["\'][^>]*>.*?<a[^>]*>(.*?)<\/a>.*?<\/h3>/ims';
preg_match_all($pattern, $content, $matches);

// In ra mảng kết quả
echo "<h3>Mảng chứa các tiêu đề:</h3>";
echo "<pre>";
print_r($matches);
echo "</pre>";

// Duyệt mảng và hiển thị trong table
echo "<h3>Danh sách tiêu đề bài viết:</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>STT</th><th>Tiêu đề bài viết</th></tr>";

if (!empty($matches[1])) {
    $count = 1;
    foreach ($matches[1] as $title) {
        $text = trim(strip_tags($title));
        echo "<tr>";
        echo "<td>{$count}</td>";
        echo "<td>{$text}</td>";
        echo "</tr>";
        $count++;
    }
} else {
    echo "<tr><td colspan='2'>Không tìm thấy tiêu đề bài viết</td></tr>";
}

echo "</table>";
?>

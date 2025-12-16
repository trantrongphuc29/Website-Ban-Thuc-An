<?php
    header("Content-Type: text/html; charset=UTF-8");
    $url = 'https://vnuk.com/';

    // Sử dụng cURL để tải trang
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64)");

    $html = curl_exec($ch);
    curl_close($ch);

    // Kiểm tra nếu lấy nội dung thành công
    if (!$html) {
        die("Không thể tải trang <em>$url</em>");
    } else {
        echo "✔️ Tải thành công trang: $url<br>";
    }

    // Tạo đối tượng DOMDocument để phân tích HTML
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($html);

    // Tìm các tiêu đề tin tức
    $xpath = new DOMXPath($doc);

    // XPath chính: lấy <a> bên trong <h2 class="uk-h5 uk-margin-small">
    $nodes = $xpath->query('//h2[@class="uk-h5 uk-margin-small"]/a');

    echo "<h2>Tiêu đề tin trang Thầy Nghĩa:</h2>";
    if ($nodes->length > 0) {
        foreach ($nodes as $node) {
            $title = trim($node->nodeValue);
            /** @var DOMelement $node  */   // Khai báo kiểu cho biến $node
            $link = $node->getAttribute('href');
            echo "<p><a href='$link' target='_blank'>$title</a></p>";
        }
    } else {
        echo "<p>Không tìm thấy tiêu đề nào. Kiểm tra lại XPath hoặc cấu trúc HTML.</p>";
    }
?>
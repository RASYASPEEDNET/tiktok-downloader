<?php
$download = null;
$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["url"])) {
  $url = $_POST["url"];

  // Panggil API TikWM
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://www.tikwm.com/api/");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "url=" . urlencode($url));
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/x-www-form-urlencoded"
  ]);
  $response = curl_exec($ch);
  curl_close($ch);

  $json = json_decode($response, true);

  if (isset($json["data"])) {
    $download = [
      "no_watermark" => $json["data"]["play"],
      "watermark"    => $json["data"]["wmplay"],
      "music"        => $json["data"]["music"],
      "title"        => $json["data"]["title"],
      "cover"        => $json["data"]["cover"]
    ];
  } else {
    $error = "Gagal mengambil data dari API!";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>TikTok Downloader</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen">
  <div class="bg-gray-800 p-8 rounded-2xl shadow-xl w-full max-w-md">
    <h1 class="text-2xl font-bold text-center mb-4">TikTok Downloader</h1>
    <form method="post">
      <input name="url" type="text" placeholder="Tempel URL TikTok"
        class="w-full p-2 rounded-lg text-black mb-4">
      <button type="submit" 
        class="w-full bg-green-500 hover:bg-green-600 p-2 rounded-lg font-bold">
        Download
      </button>
    </form>

    <div class="mt-6 text-center">
      <?php if ($download): ?>
        <div class="mb-4">
          <img src="<?= $download['cover'] ?>" alt="thumbnail" class="rounded-lg shadow-md mb-2">
          <p class="text-sm italic"><?= htmlspecialchars($download['title']) ?></p>
        </div>

        <a href="<?= $download['no_watermark'] ?>" target="_blank"
          class="block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg mb-2">
          🎥 Download Tanpa Watermark
        </a>

        <a href="<?= $download['watermark'] ?>" target="_blank"
          class="block bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg mb-2">
          🎥 Download Dengan Watermark
        </a>

        <a href="<?= $download['music'] ?>" target="_blank"
          class="block bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg">
          🎵 Download Audio (MP3)
        </a>
      <?php elseif ($error): ?>
        <p class="text-red-500"><?= $error ?></p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>

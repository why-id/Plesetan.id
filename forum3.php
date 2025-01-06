<?php
session_start();

$file = 'data.json';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $author = $_POST['author'];
    $komentar = $_POST['komentar'];

$hari = array(
    'Sunday' => 'Minggu',
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu'
);

$bulan = array(
    'January' => 'Januari',
    'February' => 'Februari',
    'March' => 'Maret',
    'April' => 'April',
    'May' => 'Mei',
    'June' => 'Juni',
    'July' => 'Juli',
    'August' => 'Agustus',
    'September' => 'September',
    'October' => 'Oktober',
    'November' => 'November',
    'December' => 'Desember'
);
date_default_timezone_set('Asia/Jakarta');
$waktu = date("H:i · d/m/y");
$waktu = str_replace(array_keys($hari), array_values($hari), $waktu);
$waktu = str_replace(array_keys($bulan), array_values($bulan), $waktu);

    $penilaianData = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

    $penilaianData[] = [
        'author' => $author,
        'komentar' => $komentar,
        'waktu' => $waktu,
    ];

    file_put_contents($file, json_encode($penilaianData, JSON_PRETTY_PRINT));

    $_SESSION['author'] = $author;
    $_SESSION['komentar'] = $komentar;
    $_SESSION['waktu'] = $waktu;
}

$penilaianData = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$perPage = 3;
$totalPenilaian = count($penilaianData);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$startIndex = ($page - 1) * $perPage;
$penilaianDataToShow = array_slice($penilaianData, $startIndex, $perPage);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plesetan.id - Posting kata-kata lu bjir</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Vinyl&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=sick" />
    <style>
/*Copyright cok! YT: DexSkieee Official
BUAT GINI AJA MASA GABISA SIH TOLOL
YATEM BIADAP PAKE VIEW-SOURCE SEGALA!!!
*/
        body {
            font-family: 'PT Sans', sans-serif;
            background: #151B54;
            color: #fff;
            padding: 20px;
            font-size: 0.9rem;
        }
        h2 {
            font-family: 'Rubik Vinyl', serif;
        }
        .edit-icons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            padding: 0 10px;
        }

        .edit-icons i {
            color: #1d9bf0;
            cursor: pointer;
            font-size: 18px; /* Ukuran ikon yang konsisten */
            margin-right: 15px; /* Jarak antar ikon */
        }

        .edit-icons i:last-child {
            margin-right: 0; /* Menghapus margin pada ikon terakhir */
        }
        .stat {
            display: flex;
            align-items: center;
            color: #aaa;
            font-size: 0.8em;
            flex-grow: 1;
            justify-content: center;
        }

        .stat .stat-number {
            font-weight: bold;
            color: #fff; /* Warna angka sama dengan tweet-content */
            margin-right: 4px;
        }
        .container {
            max-width: 700px;
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
        }

        .slide {
            flex: 0 0 100%;
            scroll-snap-align: start;
            padding: 20px;
            border-radius: 10px;
            background: transparent;
            overflow: hidden;
        }

        .form-control {
            background: #151515;
            border: none;
            color: white;
        }
        .verified-icon {
            width: 24px;
            height: 24px;
            margin-left: 4px;
        }
        .tweet-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
            border-top: 1px solid #333;
            padding-top: 8px;
        }
        .form-control:focus {
            background: #151515;
            border: none;
            color: white;
        }

        .rating {
            direction: rtl;
            display: flex;
            justify-content: center;
        }

        .rating input {
            display: none;
        }

        .rating label {
            color: #555;
            cursor: pointer;
        }

        .rating input:checked ~ label,
        .rating label:hover,
        .rating label:hover ~ label {
            color: gold;
        }

        button {
            width: 100%;
        }

        .stars {
            color: gold;
            text-align: left;
        }

        .material-icons {
            font-size: 1.8rem;
            color: #ccc;
        }

        .rating input:checked ~ label .material-icons,
        .rating label:hover .material-icons,
        .rating label:hover ~ label .material-icons {
            color: gold;
        }

        .penilaian {
            background-color: #151515;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-family: 'Varela Round', Arial, sans-serif;
        }

        .waktu-post {
            font-size: 0.7rem;
            color: #aaa;
            text-align: left;
        }

        .pagination {
            justify-content: center;
        }

        .pagination .page-link {
            color: #fff;
        }

        .pagination .page-item.active .page-link {
            background-color: transparent;
            border: none;
        }

        textarea.form-control {
            width: 100%;
            height: 350px;  /* Sesuaikan tinggi sesuai kebutuhan */
            resize: none;   /* Menghapus kemampuan resize */
        }
a {
    color: #1DA1F2;
}
a.page-link {
    background-color: transparent;
    border:none;
    color: red;
    text-decoration: none;
}

a.page-link:hover {
    background-color: transparent;
    color: #fff;
    border:none;
}

a.page-link:focus, a.page-link:active {
    background-color: transparent;
    color: #fff;
    border:none;
}
    </style>
</head>
<body>

<div class="container">
    <div class="slide">
        <h2 class="text-center mb-4" style="font-family: "Rubik Vinyl", serif;"><span class="material-symbols-outlined">sick</span> Plesetan.id</h2>
        <form action="" method="POST" id="formPenilaian">
            <div class="form-group">
                <input type="text" class="form-control" id="author" name="author" placeholder="Nama lu" required>
            </div>

            <div class="form-group">
                <textarea class="form-control" id="komentar" name="komentar" placeholder="Apa yang mau lu ceritakan..." rows="3" maxlength="150" required></textarea>
            </div>
            <div id="charCount" class="char-count" style="text-align: right;">666/666</div>

            <button type="submit" class="btn btn-outline-primary mt-3">Posting </button>
        </form>
    </div>

    <div class="slide">
        <?php if (!empty($penilaianDataToShow)): ?>
            <?php foreach ($penilaianDataToShow as $penilaian): ?>
                <div class="penilaian">
                    <img src="https://www.gravatar.com/avatar/[HASH_EMAIL]?s=30&d=mp" alt="Avatar" style="border-radius: 50%; margin-right: 10px;">
<font style="font-weight: bold;font-family: 'Varela Round', Arial, sans-serif;"><?php echo htmlspecialchars($penilaian['author']); ?></font>
<img src="https://kieza17.github.io/img/ver.png" alt="Verified" class="verified-icon">
                    <p class="waktu-post"><?php echo htmlspecialchars($penilaian['waktu']); ?> · <a href="#">Twitter for iPhone</a></p>
                    <p style="font-family: 'Varela Round', Arial, sans-serif;">"<?php echo htmlspecialchars($penilaian['komentar']); ?>"</p>

<div class="tweet-stats">
            <div class="stat">
                <span class="stat-number">666</span> Retweets
            </div>
            <div class="stat">
                <span class="stat-number">666</span> Quote Tweets
            </div>
            <div class="stat">
                <span class="stat-number">66.666</span> Likes
            </div>
        </div>
        <div class="edit-icons">
      <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true" data-toggle="modal" data-target="#editModal">
        <g>
          <path
            d="M14.046 2.242l-4.148-.01h-.002c-4.374 0-7.8 3.427-7.8 7.802 0 4.098 3.186 7.206 7.465 7.37v3.828c0 .108.044.286.12.403.142.225.384.347.632.347.138 0 .277-.038.402-.118.264-.168 6.473-4.14 8.088-5.506 1.902-1.61 3.04-3.97 3.043-6.312v-.017c-.006-4.367-3.43-7.787-7.8-7.788zm3.787 12.972c-1.134.96-4.862 3.405-6.772 4.643V16.67c0-.414-.335-.75-.75-.75h-.396c-3.66 0-6.318-2.476-6.318-5.886 0-3.534 2.768-6.302 6.3-6.302l4.147.01h.002c3.532 0 6.3 2.766 6.302 6.296-.003 1.91-.942 3.844-2.514 5.176z">
          </path>
        </g>
      </svg>
      <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true">
        <g>
          colo
          <path
            d="M23.77 15.67c-.292-.293-.767-.293-1.06 0l-2.22 2.22V7.65c0-2.068-1.683-3.75-3.75-3.75h-5.85c-.414 0-.75.336-.75.75s.336.75.75.75h5.85c1.24 0 2.25 1.01 2.25 2.25v10.24l-2.22-2.22c-.293-.293-.768-.293-1.06 0s-.294.768 0 1.06l3.5 3.5c.145.147.337.22.53.22s.383-.072.53-.22l3.5-3.5c.294-.292.294-.767 0-1.06zm-10.66 3.28H7.26c-1.24 0-2.25-1.01-2.25-2.25V6.46l2.22 2.22c.148.147.34.22.532.22s.384-.073.53-.22c.293-.293.293-.768 0-1.06l-3.5-3.5c-.293-.294-.768-.294-1.06 0l-3.5 3.5c-.294.292-.294.767 0 1.06s.767.293 1.06 0l2.22-2.22V16.7c0 2.068 1.683 3.75 3.75 3.75h5.85c.414 0 .75-.336.75-.75s-.337-.75-.75-.75z">
          </path>
        </g>
      </svg>
      <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true">
        <g>
          <path
            d="M12 21.638h-.014C9.403 21.59 1.95 14.856 1.95 8.478c0-3.064 2.525-5.754 5.403-5.754 2.29 0 3.83 1.58 4.646 2.73.814-1.148 2.354-2.73 4.645-2.73 2.88 0 5.404 2.69 5.404 5.755 0 6.376-7.454 13.11-10.037 13.157H12zM7.354 4.225c-2.08 0-3.903 1.988-3.903 4.255 0 5.74 7.034 11.596 8.55 11.658 1.518-.062 8.55-5.917 8.55-11.658 0-2.267-1.823-4.255-3.903-4.255-2.528 0-3.94 2.936-3.952 2.965-.23.562-1.156.562-1.387 0-.014-.03-1.425-2.965-3.954-2.965z">
          </path>
        </g>
      </svg>
      <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true">
        <g>
          <path
            d="M17.53 7.47l-5-5c-.293-.293-.768-.293-1.06 0l-5 5c-.294.293-.294.768 0 1.06s.767.294 1.06 0l3.72-3.72V15c0 .414.336.75.75.75s.75-.336.75-.75V4.81l3.72 3.72c.146.147.338.22.53.22s.384-.072.53-.22c.293-.293.293-.767 0-1.06z">
          </path>
          <path
            d="M19.708 21.944H4.292C3.028 21.944 2 20.916 2 19.652V14c0-.414.336-.75.75-.75s.75.336.75.75v5.652c0 .437.355.792.792.792h15.416c.437 0 .792-.355.792-.792V14c0-.414.336-.75.75-.75s.75.336.75.75v5.652c0 1.264-1.028 2.292-2.292 2.292z">
          </path>
        </g>
      </svg>
</div></div><br>
            <?php endforeach; ?>

            <div class="pagination">
                <?php 
                $totalPages = ceil($totalPenilaian / $perPage);
                for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="page-link"><p><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        <?php else: ?>
            <p>Masih kosong bjir.</p>
        <?php endif; ?>
    </div>
</div>
 <script>
    const textarea = document.getElementById('komentar');
    const charCount = document.getElementById('charCount');
    const maxLength = textarea.getAttribute('maxlength');

    textarea.addEventListener('input', function() {
      const remainingChars = maxLength - textarea.value.length;
      charCount.textContent = `${remainingChars}/666`;
    });
  </script>
</body>
</html>

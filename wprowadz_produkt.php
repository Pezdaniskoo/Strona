<?php
require_once 'admin_guard.php';
require_once 'layout.php';
require_once 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nazwa = trim($_POST['nazwa'] ?? '');
    $opis = trim($_POST['opis'] ?? '');
    $cena = (float)($_POST['cena_dzien'] ?? 0);
    $dostepny = isset($_POST['dostepny']) ? 1 : 0;
    $imageUrl = trim($_POST['image_url'] ?? '');

    if ($nazwa && $cena >= 0 && $imageUrl) {
        $stmt = $mysqli->prepare('INSERT INTO produkt (nazwa, opis, cena_dzien, dostepny, image_url) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('ssdis', $nazwa, $opis, $cena, $dostepny, $imageUrl);
        if ($stmt->execute()) {
            $message = 'Produkt zostal dodany.';
        } else {
            $message = 'Blad zapisu: ' . $mysqli->error;
        }
        $stmt->close();
    } else {
        $message = 'Uzupelnij poprawnie formularz (w tym link do zdjecia).';
    }
}

render_header('Dodaj produkt', $projectName, $adminMenu);
?>
<section>
    <h1>Dodaj produkt</h1>
    <?php if ($message): ?>
        <div class="notice"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form class="form" method="post">
        <label>
            Nazwa
            <input type="text" name="nazwa" required>
        </label>
        <label>
            Opis
            <textarea name="opis" rows="4"></textarea>
        </label>
        <label>
            Cena za dzien (PLN)
            <input type="number" name="cena_dzien" min="0" step="0.01" required>
        </label>
        <label>
            Link do zdjecia (URL)
            <input type="url" name="image_url" required>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="dostepny" checked>
            Dostepny od reki
        </label>
        <button type="submit">Zapisz produkt</button>
    </form>
</section>
<?php
render_footer();

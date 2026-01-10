<?php
require_once 'admin_guard.php';
require_once 'layout.php';
require_once 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imie = trim($_POST['imie'] ?? '');
    $nazwisko = trim($_POST['nazwisko'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');

    if ($imie && $nazwisko && $email && $telefon) {
        $stmt = $mysqli->prepare('INSERT INTO klient (imie, nazwisko, email, telefon) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $imie, $nazwisko, $email, $telefon);
        if ($stmt->execute()) {
            $message = 'Klient zostal dodany.';
        } else {
            $message = 'Blad zapisu: ' . $mysqli->error;
        }
        $stmt->close();
    } else {
        $message = 'Uzupelnij wszystkie pola.';
    }
}

render_header('Dodaj klienta', $projectName, $adminMenu);
?>
<section>
    <h1>Dodaj klienta</h1>
    <?php if ($message): ?>
        <div class="notice"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form class="form" method="post">
        <label>
            Imie
            <input type="text" name="imie" required>
        </label>
        <label>
            Nazwisko
            <input type="text" name="nazwisko" required>
        </label>
        <label>
            Email
            <input type="email" name="email" required>
        </label>
        <label>
            Telefon
            <input type="text" name="telefon" required>
        </label>
        <button type="submit">Zapisz klienta</button>
    </form>
</section>
<?php
render_footer();

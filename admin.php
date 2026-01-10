<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'layout.php';

$message = '';

if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: admin.php');
    exit;
}

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($requestMethod === 'POST') {
    $password = $_POST['password'] ?? '';
    if ($password === 'admin') {
        $_SESSION['is_admin'] = true;
        header('Location: admin.php');
        exit;
    }
    $message = 'Nieprawidlowe haslo.';
}

$loggedIn = !empty($_SESSION['is_admin']);

render_header('Panel administratora', $projectName, $loggedIn ? $adminMenu : $publicMenu);
?>
<section class="card">
    <h1>Panel administratora</h1>

    <?php if ($loggedIn): ?>
        <p>Witaj w panelu admina. Wybierz funkcje zarzadzania:</p>
        <div class="admin-grid">
            <a class="admin-card" href="wprowadz_klient.php">Dodaj klienta</a>
            <a class="admin-card" href="mod_klient.php">Modyfikuj klienta</a>
            <a class="admin-card" href="kasuj_klient.php">Usun klienta</a>
            <a class="admin-card" href="wprowadz_produkt.php">Dodaj pojazd</a>
            <a class="admin-card" href="kasuj_produkt.php">Usun pojazd</a>
            <a class="admin-card" href="zwroc.php">Zwroty</a>
            <a class="admin-card" href="wyswietl.php">Wyswietl dane</a>
            <a class="admin-card" href="raport.php">Raporty</a>
            <a class="admin-card" href="baza.php">Struktura bazy</a>
        </div>
        <a class="menu__link" href="admin.php?logout=1">Wyloguj</a>
    <?php else: ?>
        <?php if ($message): ?>
            <div class="notice"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form class="form" method="post">
            <label>
                Haslo admina
                <input type="password" name="password" required>
            </label>
            <button type="submit">Zaloguj</button>
        </form>
    <?php endif; ?>
</section>
<?php
render_footer();

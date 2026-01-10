<?php
require_once 'admin_guard.php';
require_once 'layout.php';
require_once 'db.php';

$message = '';
$selectedId = (int)($_GET['id_klient'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedId = (int)($_POST['id_klient'] ?? 0);
    $imie = trim($_POST['imie'] ?? '');
    $nazwisko = trim($_POST['nazwisko'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');

    if ($selectedId && $imie && $nazwisko && $email && $telefon) {
        $stmt = $mysqli->prepare('UPDATE klient SET imie = ?, nazwisko = ?, email = ?, telefon = ? WHERE id_klient = ?');
        $stmt->bind_param('ssssi', $imie, $nazwisko, $email, $telefon, $selectedId);
        if ($stmt->execute()) {
            $message = 'Dane klienta zostaly zaktualizowane.';
        } else {
            $message = 'Blad aktualizacji: ' . $mysqli->error;
        }
        $stmt->close();
    } else {
        $message = 'Uzupelnij wszystkie pola.';
    }
}

$klienci = $mysqli->query('SELECT id_klient, imie, nazwisko FROM klient ORDER BY nazwisko');
$klient = null;
if ($selectedId) {
    $stmt = $mysqli->prepare('SELECT id_klient, imie, nazwisko, email, telefon FROM klient WHERE id_klient = ?');
    $stmt->bind_param('i', $selectedId);
    $stmt->execute();
    $klient = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

render_header('Modyfikuj klienta', $projectName, $adminMenu);
?>
<section>
    <h1>Modyfikuj klienta</h1>
    <?php if ($message): ?>
        <div class="notice"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="get" class="form">
        <label>
            Wybierz klienta
            <select name="id_klient" onchange="this.form.submit()" required>
                <option value="">-- wybierz --</option>
                <?php if ($klienci): ?>
                    <?php while ($row = $klienci->fetch_assoc()): ?>
                        <option value="<?php echo (int)$row['id_klient']; ?>" <?php echo $selectedId === (int)$row['id_klient'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['imie'] . ' ' . $row['nazwisko']); ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>
        </label>
    </form>

    <?php if ($klient): ?>
        <form method="post" class="form">
            <input type="hidden" name="id_klient" value="<?php echo (int)$klient['id_klient']; ?>">
            <label>
                Imie
                <input type="text" name="imie" value="<?php echo htmlspecialchars($klient['imie']); ?>" required>
            </label>
            <label>
                Nazwisko
                <input type="text" name="nazwisko" value="<?php echo htmlspecialchars($klient['nazwisko']); ?>" required>
            </label>
            <label>
                Email
                <input type="email" name="email" value="<?php echo htmlspecialchars($klient['email']); ?>" required>
            </label>
            <label>
                Telefon
                <input type="text" name="telefon" value="<?php echo htmlspecialchars($klient['telefon']); ?>" required>
            </label>
            <button type="submit">Zapisz zmiany</button>
        </form>
    <?php elseif ($selectedId): ?>
        <p>Nie znaleziono klienta.</p>
    <?php endif; ?>
</section>
<?php
render_footer();

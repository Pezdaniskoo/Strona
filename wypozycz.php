<?php
require_once 'layout.php';
require_once 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imie = trim($_POST['imie'] ?? '');
    $nazwisko = trim($_POST['nazwisko'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $idProdukt = (int)($_POST['id_produkt'] ?? 0);
    $data = $_POST['data_wypoze'] ?? '';

    if ($imie && $nazwisko && $email && $telefon && $idProdukt && $data) {
        $stmt = $mysqli->prepare('SELECT id_klient FROM klient WHERE imie = ? AND nazwisko = ? AND email = ? AND telefon = ?');
        $stmt->bind_param('ssss', $imie, $nazwisko, $email, $telefon);
        $stmt->execute();
        $klient = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($klient) {
            $idKlient = (int)$klient['id_klient'];
            $mysqli->begin_transaction();
            try {
                $stmt = $mysqli->prepare('INSERT INTO wypozyczenie (id_klient, id_produkt, data_wypoze) VALUES (?, ?, ?)');
                $stmt->bind_param('iis', $idKlient, $idProdukt, $data);
                $stmt->execute();
                $stmt->close();

                $stmt = $mysqli->prepare('UPDATE produkt SET dostepny = 0 WHERE id_produkt = ?');
                $stmt->bind_param('i', $idProdukt);
                $stmt->execute();
                $stmt->close();

                $mysqli->commit();
                $message = 'Wypozyczenie zostalo zapisane.';
            } catch (Throwable $exception) {
                $mysqli->rollback();
                $message = 'Blad zapisu wypozyczenia.';
            }
        } else {
            $message = 'Nie znaleziono klienta. Zarejestruj sie na stronie glownej.';
        }
    } else {
        $message = 'Uzupelnij wszystkie pola.';
    }
}

$produkty = $mysqli->query('SELECT id_produkt, nazwa FROM produkt WHERE dostepny = 1 ORDER BY nazwa');

render_header('Wypozycz', $projectName, $publicMenu);
?>
<section>
    <h1>Wypozycz produkt</h1>
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
        <label>
            Produkt
            <select name="id_produkt" required>
                <option value="">-- wybierz --</option>
                <?php if ($produkty): ?>
                    <?php while ($row = $produkty->fetch_assoc()): ?>
                        <option value="<?php echo (int)$row['id_produkt']; ?>">
                            <?php echo htmlspecialchars($row['nazwa']); ?>
                        </option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="">Brak dostepnych aut</option>
                <?php endif; ?>
            </select>
        </label>
        <label>
            Data wypozyczenia
            <input type="date" name="data_wypoze" required>
        </label>
        <button type="submit">Zapisz wypozyczenie</button>
    </form>
</section>
<?php
render_footer();

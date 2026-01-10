<?php
require_once 'admin_guard.php';
require_once 'layout.php';
require_once 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idWypozyczenie = (int)($_POST['id_wypozyczenie'] ?? 0);
    $dataZwrotu = $_POST['data_zwrotu'] ?? '';

    if ($idWypozyczenie && $dataZwrotu) {
        $stmt = $mysqli->prepare('SELECT w.data_wypoze, p.cena_dzien, w.id_produkt FROM wypozyczenie w JOIN produkt p ON w.id_produkt = p.id_produkt WHERE w.id_wypozyczenie = ?');
        $stmt->bind_param('i', $idWypozyczenie);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($result) {
            $dataStart = new DateTime($result['data_wypoze']);
            $dataEnd = new DateTime($dataZwrotu);
            $diffDays = (int)$dataStart->diff($dataEnd)->format('%a');
            $liczbaDni = max(1, $diffDays);
            $koszt = $liczbaDni * (float)$result['cena_dzien'];

            $mysqli->begin_transaction();
            try {
                $stmt = $mysqli->prepare('UPDATE wypozyczenie SET data_zwrotu = ?, koszt = ? WHERE id_wypozyczenie = ?');
                $stmt->bind_param('sdi', $dataZwrotu, $koszt, $idWypozyczenie);
                $stmt->execute();
                $stmt->close();

                $stmt = $mysqli->prepare('UPDATE produkt SET dostepny = 1 WHERE id_produkt = ?');
                $stmt->bind_param('i', $result['id_produkt']);
                $stmt->execute();
                $stmt->close();

                $mysqli->commit();
                $message = 'Zwrot zapisany. Koszt: ' . number_format($koszt, 2, ',', ' ') . ' PLN.';
            } catch (Throwable $exception) {
                $mysqli->rollback();
                $message = 'Blad zapisu zwrotu.';
            }
        } else {
            $message = 'Nie znaleziono wypozyczenia.';
        }
    } else {
        $message = 'Uzupelnij wszystkie pola.';
    }
}

$wypozyczenia = $mysqli->query('SELECT w.id_wypozyczenie, k.imie, k.nazwisko, p.nazwa, w.data_wypoze FROM wypozyczenie w JOIN klient k ON w.id_klient = k.id_klient JOIN produkt p ON w.id_produkt = p.id_produkt WHERE w.data_zwrotu IS NULL ORDER BY w.data_wypoze');

render_header('Zwroc', $projectName, $adminMenu);
?>
<section>
    <h1>Zwrot produktu</h1>
    <?php if ($message): ?>
        <div class="notice"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form class="form" method="post">
        <label>
            Aktywne wypozyczenie
            <select name="id_wypozyczenie" required>
                <option value="">-- wybierz --</option>
                <?php if ($wypozyczenia): ?>
                    <?php while ($row = $wypozyczenia->fetch_assoc()): ?>
                        <option value="<?php echo (int)$row['id_wypozyczenie']; ?>">
                            <?php echo htmlspecialchars($row['imie'] . ' ' . $row['nazwisko'] . ' - ' . $row['nazwa'] . ' (' . $row['data_wypoze'] . ')'); ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>
        </label>
        <label>
            Data zwrotu
            <input type="date" name="data_zwrotu" required>
        </label>
        <button type="submit">Zakoncz wypozyczenie</button>
    </form>
</section>
<?php
render_footer();

<?php
require_once 'admin_guard.php';
require_once 'layout.php';
require_once 'db.php';

$klienci = $mysqli->query('SELECT id_klient, imie, nazwisko, email, telefon FROM klient ORDER BY nazwisko');
$produkty = $mysqli->query('SELECT id_produkt, nazwa, cena_dzien, dostepny FROM produkt ORDER BY nazwa');
$wypozyczenia = $mysqli->query('SELECT w.id_wypozyczenie, k.imie, k.nazwisko, p.nazwa, w.data_wypoze FROM wypozyczenie w JOIN klient k ON w.id_klient = k.id_klient JOIN produkt p ON w.id_produkt = p.id_produkt WHERE w.data_zwrotu IS NULL ORDER BY w.data_wypoze DESC');

render_header('Wyswietl dane', $projectName, $adminMenu);
?>
<section>
    <h1>Lista danych</h1>

    <div class="card">
        <h2>Klienci</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imie</th>
                    <th>Nazwisko</th>
                    <th>Email</th>
                    <th>Telefon</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($klienci && $klienci->num_rows > 0): ?>
                    <?php while ($row = $klienci->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo (int)$row['id_klient']; ?></td>
                            <td><?php echo htmlspecialchars($row['imie']); ?></td>
                            <td><?php echo htmlspecialchars($row['nazwisko']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['telefon']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5">Brak klientow.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>Produkty</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nazwa</th>
                    <th>Cena/dzien</th>
                    <th>Dostepny</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($produkty && $produkty->num_rows > 0): ?>
                    <?php while ($row = $produkty->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo (int)$row['id_produkt']; ?></td>
                            <td><?php echo htmlspecialchars($row['nazwa']); ?></td>
                            <td><?php echo number_format((float)$row['cena_dzien'], 2, ',', ' '); ?> PLN</td>
                            <td><?php echo $row['dostepny'] ? 'Tak' : 'Nie'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4">Brak produktow.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>Aktywne wypozyczenia</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Klient</th>
                    <th>Produkt</th>
                    <th>Data wypozyczenia</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($wypozyczenia && $wypozyczenia->num_rows > 0): ?>
                    <?php while ($row = $wypozyczenia->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo (int)$row['id_wypozyczenie']; ?></td>
                            <td><?php echo htmlspecialchars($row['imie'] . ' ' . $row['nazwisko']); ?></td>
                            <td><?php echo htmlspecialchars($row['nazwa']); ?></td>
                            <td><?php echo htmlspecialchars($row['data_wypoze']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4">Brak aktywnych wypozyczen.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php
render_footer();

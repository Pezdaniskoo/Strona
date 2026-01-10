<?php
require_once 'admin_guard.php';
require_once 'layout.php';
require_once 'db.php';

$start = $_GET['start'] ?? '';
$end = $_GET['end'] ?? '';
$raport = [];
$total = 0;

if ($start && $end) {
    $stmt = $mysqli->prepare('SELECT k.imie, k.nazwisko, SUM(IFNULL(w.koszt, 0)) AS suma FROM wypozyczenie w JOIN klient k ON w.id_klient = k.id_klient WHERE w.data_wypoze BETWEEN ? AND ? GROUP BY w.id_klient ORDER BY suma DESC');
    $stmt->bind_param('ss', $start, $end);
    $stmt->execute();
    $raport = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    foreach ($raport as $row) {
        $total += (float)$row['suma'];
    }
}

render_header('Raport', $projectName, $adminMenu);
?>
<section>
    <h1>Raport wypozyczen</h1>
    <form class="form" method="get">
        <label>
            Od
            <input type="date" name="start" value="<?php echo htmlspecialchars($start); ?>" required>
        </label>
        <label>
            Do
            <input type="date" name="end" value="<?php echo htmlspecialchars($end); ?>" required>
        </label>
        <button type="submit">Generuj raport</button>
    </form>

    <?php if ($start && $end): ?>
        <div class="card">
            <h2>Podsumowanie</h2>
            <table>
                <thead>
                    <tr>
                        <th>Klient</th>
                        <th>Suma kosztow</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($raport): ?>
                        <?php foreach ($raport as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['imie'] . ' ' . $row['nazwisko']); ?></td>
                                <td><?php echo number_format((float)$row['suma'], 2, ',', ' '); ?> PLN</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="2">Brak danych dla wybranego okresu.</td></tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Razem</th>
                        <th><?php echo number_format($total, 2, ',', ' '); ?> PLN</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php
render_footer();

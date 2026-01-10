<?php
require_once 'admin_guard.php';
require_once 'layout.php';
require_once 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id_produkt'] ?? 0);
    if ($id > 0) {
        $stmt = $mysqli->prepare('DELETE FROM produkt WHERE id_produkt = ?');
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $message = 'Produkt zostal usuniety.';
        } else {
            $message = 'Nie mozna usunac produktu: ' . $mysqli->error;
        }
        $stmt->close();
    }
}

$produkty = $mysqli->query('SELECT id_produkt, nazwa, cena_dzien FROM produkt ORDER BY nazwa');

render_header('Usun produkt', $projectName, $adminMenu);
?>
<section>
    <h1>Usun produkt</h1>
    <?php if ($message): ?>
        <div class="notice"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nazwa</th>
                <th>Cena/dzien</th>
                <th>Akcja</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($produkty && $produkty->num_rows > 0): ?>
                <?php while ($row = $produkty->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo (int)$row['id_produkt']; ?></td>
                        <td><?php echo htmlspecialchars($row['nazwa']); ?></td>
                        <td><?php echo number_format((float)$row['cena_dzien'], 2, ',', ' '); ?> PLN</td>
                        <td>
                            <form method="post" onsubmit="return confirm('Usunac produkt?');">
                                <input type="hidden" name="id_produkt" value="<?php echo (int)$row['id_produkt']; ?>">
                                <button type="submit" class="danger">Usun</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">Brak produktow do usuniecia.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>
<?php
render_footer();

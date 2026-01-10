<?php
require_once 'admin_guard.php';
require_once 'layout.php';
require_once 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id_klient'] ?? 0);
    if ($id > 0) {
        $stmt = $mysqli->prepare('DELETE FROM klient WHERE id_klient = ?');
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $message = 'Klient zostal usuniety.';
        } else {
            $message = 'Nie mozna usunac klienta: ' . $mysqli->error;
        }
        $stmt->close();
    }
}

$klienci = $mysqli->query('SELECT id_klient, imie, nazwisko, email FROM klient ORDER BY nazwisko');

render_header('Usun klienta', $projectName, $adminMenu);
?>
<section>
    <h1>Usun klienta</h1>
    <?php if ($message): ?>
        <div class="notice"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Imie i nazwisko</th>
                <th>Email</th>
                <th>Akcja</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($klienci && $klienci->num_rows > 0): ?>
                <?php while ($row = $klienci->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo (int)$row['id_klient']; ?></td>
                        <td><?php echo htmlspecialchars($row['imie'] . ' ' . $row['nazwisko']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <form method="post" onsubmit="return confirm('Usunac klienta?');">
                                <input type="hidden" name="id_klient" value="<?php echo (int)$row['id_klient']; ?>">
                                <button type="submit" class="danger">Usun</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">Brak klientow do usuniecia.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>
<?php
render_footer();

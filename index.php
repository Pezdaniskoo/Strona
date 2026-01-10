<?php
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
            $message = 'Rejestracja zakonczona. Zapraszamy do wypozyczenia auta.';
        } else {
            $message = 'Blad zapisu: ' . $mysqli->error;
        }
        $stmt->close();
    } else {
        $message = 'Uzupelnij wszystkie pola rejestracji.';
    }
}

$dostepneAuta = $mysqli->query('SELECT id_produkt, nazwa, opis, cena_dzien, image_url FROM produkt WHERE dostepny = 1 ORDER BY nazwa');

render_header('Strona glowna', $projectName, $publicMenu);
?>
<section class="hero">
    <div>
        <h1>Auto Complex</h1>
        <p>Nowoczesna wypozyczalnia samochodow, ktora wspiera pelny cykl obslugi klienta.</p>
        <ul>
            <li>Rejestrowanie klientow i pojazdow.</li>
            <li>Obsluga wypozyczen i zwrotow.</li>
            <li>Raporty kosztow w dowolnym okresie.</li>
        </ul>
    </div>
    <div class="hero__card">
        <h2>Autor</h2>
        <p>Imie i nazwisko: <strong>Igor Harmala</strong></p>
        <p>Grupa: <strong>WebBoost-02</strong></p>
        <p>Kontakt: <strong>auto.complex@gmail.com</strong></p>
    </div>
</section>

<?php if ($message): ?>
    <div class="notice"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<section class="card">
    <h2>Rejestracja klienta</h2>
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
        <button type="submit">Zarejestruj sie</button>
    </form>
</section>

<section class="card">
    <h2>Dostepne auta do wypozyczenia</h2>
    <div class="vehicle-grid">
        <?php if ($dostepneAuta && $dostepneAuta->num_rows > 0): ?>
            <?php while ($row = $dostepneAuta->fetch_assoc()): ?>
                <article class="vehicle-card">
                    <div class="vehicle-card__image">
                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['nazwa']); ?>">
                    </div>
                    <div class="vehicle-card__body">
                        <h3><?php echo htmlspecialchars($row['nazwa']); ?></h3>
                        <p><?php echo htmlspecialchars($row['opis']); ?></p>
                        <p class="vehicle-card__price">Cena: <?php echo number_format((float)$row['cena_dzien'], 2, ',', ' '); ?> PLN / dzien</p>
                        <a class="menu__link" href="wypozycz.php">Wypozycz teraz</a>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Brak dostepnych aut. Wroc pozniej.</p>
        <?php endif; ?>
    </div>
</section>

<section class="info">
    <h2>Jak korzystac?</h2>
    <p>
        Zarejestruj sie jako klient i wybierz auto z dostepnej listy, aby zlozyc wypozyczenie.
        Panel administratora sluzy do zarzadzania klientami, pojazdami oraz raportami.
    </p>
</section>
<?php
render_footer();

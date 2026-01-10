<?php
require_once 'admin_guard.php';
require_once 'layout.php';

render_header('Struktura bazy', $projectName, $adminMenu);
?>
<section>
    <h1>Struktura bazy danych</h1>
    <p>Baza danych wspiera trzy glowne encje: klientow, produkty oraz wypozyczenia.</p>

    <div class="card">
        <h2>ERD (ASCII)</h2>
        <pre class="erd">
KLIENT (1) ────&lt; (N) WYPOZYCZENIE (N) &gt;──── (1) PRODUKT
        </pre>
    </div>

    <div class="grid">
        <div class="card">
            <h3>klient</h3>
            <ul>
                <li><strong>id_klient</strong> (INT, PK, AI)</li>
                <li><strong>imie</strong> (VARCHAR(50))</li>
                <li><strong>nazwisko</strong> (VARCHAR(50))</li>
                <li><strong>email</strong> (VARCHAR(100), UNIQUE)</li>
                <li><strong>telefon</strong> (VARCHAR(20))</li>
            </ul>
        </div>
        <div class="card">
            <h3>produkt</h3>
            <ul>
                <li><strong>id_produkt</strong> (INT, PK, AI)</li>
                <li><strong>nazwa</strong> (VARCHAR(100))</li>
                <li><strong>opis</strong> (TEXT)</li>
                <li><strong>cena_dzien</strong> (DECIMAL(10,2))</li>
                <li><strong>dostepny</strong> (TINYINT)</li>
                <li><strong>image_url</strong> (VARCHAR(255))</li>
            </ul>
        </div>
        <div class="card">
            <h3>wypozyczenie</h3>
            <ul>
                <li><strong>id_wypozyczenie</strong> (INT, PK, AI)</li>
                <li><strong>id_klient</strong> (INT, FK)</li>
                <li><strong>id_produkt</strong> (INT, FK)</li>
                <li><strong>data_wypoze</strong> (DATE)</li>
                <li><strong>data_zwrotu</strong> (DATE, NULL)</li>
                <li><strong>koszt</strong> (DECIMAL(10,2), NULL)</li>
            </ul>
        </div>
    </div>
</section>
<?php
render_footer();

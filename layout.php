<?php
$projectName = 'Auto Complex';
$publicMenu = [
    'Strona glowna' => 'index.php',
    'Wypozycz' => 'wypozycz.php',
    'Admin' => 'admin.php',
];

$adminMenu = [
    'Panel admina' => 'admin.php',
    'Struktura bazy' => 'baza.php',
    'Dodaj klienta' => 'wprowadz_klient.php',
    'Dodaj produkt' => 'wprowadz_produkt.php',
    'Wyswietl dane' => 'wyswietl.php',
    'Modyfikuj klienta' => 'mod_klient.php',
    'Usun klienta' => 'kasuj_klient.php',
    'Usun produkt' => 'kasuj_produkt.php',
    'Wypozycz' => 'wypozycz.php',
    'Zwroc' => 'zwroc.php',
    'Raport' => 'raport.php',
];

function render_header(string $title, string $projectName, array $menu): void
{
    echo "<!doctype html>\n";
    echo "<html lang=\"pl\">\n";
    echo "<head>\n";
    echo "  <meta charset=\"utf-8\">\n";
    echo "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n";
    echo "  <title>{$title} - {$projectName}</title>\n";
    echo "  <link rel=\"stylesheet\" href=\"styl.css\">\n";
    echo "</head>\n";
    echo "<body>\n";
    echo "<header class=\"site-header\">\n";
    echo "  <a class=\"logo\" href=\"index.php\" aria-label=\"Auto Complex - strona glowna\">AC</a>\n";
    echo "  <div class=\"brand\">{$projectName}</div>\n";
    echo "  <div class=\"tagline\">Wypozyczalnia samochodow klasy premium i miejskich</div>\n";
    echo "</header>\n";
    echo "<nav class=\"menu\">\n";
    foreach ($menu as $label => $link) {
        echo "  <a class=\"menu__link\" href=\"{$link}\">{$label}</a>\n";
    }
    echo "</nav>\n";
    echo "<main class=\"content\">\n";
}

function render_footer(): void
{
    echo "</main>\n";
    echo "<footer class=\"site-footer\">\n";
    echo "  <p>Auto Complex &copy; " . date('Y') . "</p>\n";
    echo "</footer>\n";
    echo "</body>\n";
    echo "</html>\n";
}

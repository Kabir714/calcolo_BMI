<?php
// Controllo del metodo GET o POST
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    header("Location: index.html");
    exit();
}

// Lettura e sanificazione dati inviati dalla form
$nome = isset($_POST['nome']) && is_string($_POST['nome']) ? htmlspecialchars(trim($_POST['nome'])) : '';
$peso = isset($_POST['peso']) && is_scalar($_POST['peso']) ? filter_var($_POST['peso'], FILTER_VALIDATE_FLOAT) : false;
$altezza_cm = isset($_POST['altezza']) && is_scalar($_POST['altezza']) ? filter_var($_POST['altezza'], FILTER_VALIDATE_FLOAT) : false;

// Validazione dei dati
$nome_valido = $nome != '';
$peso_valido = $peso !== false && $peso > 0;
$altezza_valida = $altezza_cm !== false && $altezza_cm > 0;

// Calcolo del BMI: peso / (altezza in metri)^2
$bmi = 0;
$fascia = '';
$classe_fascia = '';

if ($peso_valido && $altezza_valida) {
    $altezza_m = $altezza_cm / 100;
    $bmi = $peso / ($altezza_m * $altezza_m);

    // Determinazione della fascia secondo le regole del mockup
    if ($bmi < 18.5) {
        $fascia = 'SOTTOPESO';
        $classe_fascia = 'sottopeso';
    } elseif ($bmi <= 24.9) {
        $fascia = 'NORMOPESO';
        $classe_fascia = 'normopeso';
    } elseif ($bmi <= 29.9) {
        $fascia = 'SOVRAPPESO';
        $classe_fascia = 'sovrappeso';
    } else {
        $fascia = 'OBESITÀ';
        $classe_fascia = 'obesita';
    }
}

// Salvataggio dei dati nel file log.csv
$data = date("d/m/Y");
$ora = date("H:i:s");
$ip = $_SERVER["REMOTE_ADDR"] ?? '';

$log_nome = $nome_valido ? html_entity_decode($nome, ENT_QUOTES, 'UTF-8') : 'NOT_VALID';
$log_altezza = $altezza_valida ? $altezza_cm : 'NOT_VALID';
$log_peso = $peso_valido ? $peso : 'NOT_VALID';
$log_bmi = ($peso_valido && $altezza_valida) ? number_format($bmi, 2, '.', '') : 'NOT_VALID';
$log_classe = ($peso_valido && $altezza_valida) ? $classe_fascia : 'NOT_VALID';

$file = fopen("log.csv", "a");

if ($file) {
    fputcsv($file, [
        $data,
        $ora,
        $ip,
        $log_nome,
        $log_altezza,
        $log_peso,
        $log_bmi,
        $log_classe
    ], ",");
    fclose($file);
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Il tuo BMI</title>
    <link rel="stylesheet" href="stileComune.css">
</head>
<body>

    <main class="page-container">
        <h1>Il tuo BMI</h1>

        <!-- Card con le due classi richieste: "card" + classe della fascia -->
        <div class="card <?php echo $classe_fascia; ?>">
            <div class="card-nome"><?php echo $nome; ?></div>
            <div class="card-dati">Peso <?php echo $peso_valido ? $peso : 'Non valido'; ?> kg · Altezza <?php echo $altezza_valida ? $altezza_cm : 'Non valida'; ?> cm</div>
            <div class="card-bmi"><?php echo ($peso_valido && $altezza_valida) ? number_format($bmi, 2, '.', '') : 'Non valido'; ?></div>
            <div class="card-fascia"><?php echo $fascia; ?></div>
        </div>

        <a href="index.html" class="link-nuovo">Calcola di nuovo</a>
    </main>

</body>
</html>

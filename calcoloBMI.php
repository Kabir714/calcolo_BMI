<?php
// Lettura e sanificazione dati inviati dalla form
$nome = isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : '';
$peso = isset($_POST['peso']) ? floatval($_POST['peso']) : 0;
$altezza_cm = isset($_POST['altezza']) ? floatval($_POST['altezza']) : 0;

// Calcolo del BMI: peso / (altezza in metri)^2
$bmi = 0;
$fascia = '';
$classe_fascia = '';

if ($peso > 0 && $altezza_cm > 0) {
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
            <div class="card-dati">Peso <?php echo $peso; ?> kg · Altezza <?php echo $altezza_cm; ?> cm</div>
            <div class="card-bmi"><?php echo number_format($bmi, 2, '.', ''); ?></div>
            <div class="card-fascia"><?php echo $fascia; ?></div>
        </div>

        <a href="index.html" class="link-nuovo">Calcola di nuovo</a>
    </main>

</body>
</html>
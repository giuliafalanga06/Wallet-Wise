<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    // 1. Recupera i dati dalla richiesta
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $importo = floatval($input['importo'] ?? 0);
    $profilo = $input['profilo'] ?? 'medio';

    // 2. Validazione
    if ($importo < 10) throw new Exception("L'importo minimo è 10€");

    // 3. Mappa ETF (usando simboli più stabili)
    $etfMap = [
        'basso' => 'BND',  // Vanguard Total Bond Market
        'medio' => 'VOO',  // Vanguard S&P 500
        'alto'  => 'QQQ'   // Invesco QQQ (Nasdaq-100)
    ];
    $symbol = $etfMap[$profilo] ?? 'VOO';

    // 4. Recupera dati da TwelveData (API Key demo - sostituisci con la tua)
    $apiKey = '9aaeae89fcb749c78e81200a864d44d9'; // Registrati su twelvedata.com per una key gratuita
    $url = "https://api.twelvedata.com/time_series?symbol=$symbol&interval=1day&outputsize=30&apikey=$apiKey";

    $response = file_get_contents($url);
    if (!$response) throw new Exception("Errore nel collegamento all'API");

    $data = json_decode($response, true);
    if (isset($data['code'])) throw new Exception("API Error: {$data['message']}");

    // 5. Estrai i prezzi
    $prices = array_column($data['values'] ?? [], 'close');
    $dates = array_column($data['values'] ?? [], 'datetime');
    
    if (empty($prices)) throw new Exception("Nessun dato disponibile");

    // 6. Calcoli finanziari
    $prezzo_iniziale = (float)$prices[count($prices)-1]; // Prezzo più vecchio
    $prezzo_attuale = (float)$prices[0]; // Prezzo più recente
    $valore_attuale = ($importo / $prezzo_iniziale) * $prezzo_attuale;

    // 7. Prepara la risposta
    echo json_encode([
        'success' => true,
        'symbol' => $symbol,
        'dates' => array_reverse($dates),
        'prices' => array_reverse($prices),
        'valore_attuale' => round($valore_attuale, 2),
        'profitto' => round($valore_attuale - $importo, 2),
        'percentuale' => round((($valore_attuale - $importo) / $importo) * 100, 2)
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
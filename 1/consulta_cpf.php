<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Lidar com requisições OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Obter o CPF da requisição
$cpf = isset($_GET['cpf']) ? $_GET['cpf'] : '';

if (empty($cpf)) {
    echo json_encode(['erro' => 'CPF não fornecido']);
    exit();
}

// Remover caracteres não numéricos do CPF
$cpf = preg_replace('/[^0-9]/', '', $cpf);

// URL da API
$url = 'https://api-consultas.online/api/?cpf=' . urlencode($cpf);

// Fazer a requisição usando cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Verificar se houve erro na requisição
if ($error) {
    echo json_encode(['erro' => 'Erro na requisição: ' . $error]);
    exit();
}

// Verificar o código HTTP
if ($httpCode !== 200) {
    echo json_encode(['erro' => 'Erro HTTP: ' . $httpCode]);
    exit();
}

// Retornar a resposta da API
echo $response;
?>


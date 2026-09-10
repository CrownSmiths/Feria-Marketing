<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

// ---------------------------------------------------------
// CONFIGURACIÓN
// ---------------------------------------------------------

$dbHost = 'localhost';
$dbName = 'fs2026_crownsmith';
$dbUser = 'fs2026_crownsmith';
$dbPass = 'fs2026_crownsmith';

// ---------------------------------------------------------
// CONEXIÓN
// ---------------------------------------------------------

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['ok' => false]);
    exit;
}

// ---------------------------------------------------------
// RECIBIR JSON
// ---------------------------------------------------------

$input = json_decode(
    file_get_contents('php://input'),
    true
);

if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['ok' => false]);
    exit;
}

// ---------------------------------------------------------
// VALIDACIÓN
// ---------------------------------------------------------

$allowedEvents = [
    'page_view',

    'scroll_25',
    'scroll_50',
    'scroll_75',
    'scroll_100',

    'linkedin_team_1',
    'linkedin_team_2',
    'linkedin_team_3',
    'linkedin_team_4',
    'linkedin_team_5',
    'linkedin_team_6',

    'linkedin_startup',
    'instagram_startup',

    'mailto',

    'video_play',
    'video_25',
    'video_50',
    'video_75',
    'video_100',
    'video_complete',
];

$eventName = $input['event'] ?? null;

if (
    !is_string($eventName) ||
    !in_array($eventName, $allowedEvents, true)
) {
    http_response_code(400);
    echo json_encode(['ok' => false]);
    exit;
}

// ---------------------------------------------------------
// HELPERS
// ---------------------------------------------------------

function cleanString(
    mixed $value,
    int $maxLength
): ?string {
    if (!is_string($value)) {
        return null;
    }

    $value = trim($value);

    if ($value === '') {
        return null;
    }

    return mb_substr($value, 0, $maxLength);
}

// ---------------------------------------------------------
// DATOS DEL EVENTO
// ---------------------------------------------------------

$pagePath = cleanString(
    $input['page'] ?? null,
    255
);

$deviceType = cleanString(
    $input['device_type'] ?? null,
    20
);

$browser = cleanString(
    $input['browser'] ?? null,
    50
);

$os = cleanString(
    $input['os'] ?? null,
    50
);

$language = cleanString(
    $input['language'] ?? null,
    20
);

$referrerDomain = cleanString(
    $input['referrer_domain'] ?? null,
    255
);

// ---------------------------------------------------------
// UTM
// ---------------------------------------------------------

$utmSource = cleanString(
    $input['utm_source'] ?? null,
    100
);

$utmMedium = cleanString(
    $input['utm_medium'] ?? null,
    100
);

$utmCampaign = cleanString(
    $input['utm_campaign'] ?? null,
    150
);

$utmContent = cleanString(
    $input['utm_content'] ?? null,
    150
);

$utmTerm = cleanString(
    $input['utm_term'] ?? null,
    150
);

// ---------------------------------------------------------
// GEOLOCALIZACIÓN
// ---------------------------------------------------------

/*
 * IMPORTANTE:
 *
 * Aquí puedes integrar MaxMind GeoIP2, por ejemplo.
 *
 * La función debe devolver solamente una ubicación aproximada.
 *
 * Ejemplo:
 *
 * [
 *     'country_code' => 'CL',
 *     'region'       => 'RM',
 *     'city'         => 'Santiago'
 * ]
 *
 * No guardamos la IP en la base de datos.
 */

$countryCode = null;
$region = null;
$city = null;

/*
 * Ejemplo conceptual:
 *
 * $geo = getGeoFromIP($_SERVER['REMOTE_ADDR']);
 *
 * $countryCode = $geo['country_code'] ?? null;
 * $region      = $geo['region'] ?? null;
 * $city        = $geo['city'] ?? null;
 */

// ---------------------------------------------------------
// INSERT
// ---------------------------------------------------------

$sql = "
    INSERT INTO analytics_events (
        created_at,
        event_name,
        page_path,
        device_type,
        browser,
        os,
        language,
        country_code,
        region,
        city,
        referrer_domain,
        utm_source,
        utm_medium,
        utm_campaign,
        utm_content,
        utm_term
    )
    VALUES (
        NOW(),
        :event_name,
        :page_path,
        :device_type,
        :browser,
        :os,
        :language,
        :country_code,
        :region,
        :city,
        :referrer_domain,
        :utm_source,
        :utm_medium,
        :utm_campaign,
        :utm_content,
        :utm_term
    )
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':event_name'      => $eventName,
    ':page_path'       => $pagePath,
    ':device_type'     => $deviceType,
    ':browser'         => $browser,
    ':os'              => $os,
    ':language'        => $language,
    ':country_code'    => $countryCode,
    ':region'          => $region,
    ':city'            => $city,
    ':referrer_domain' => $referrerDomain,
    ':utm_source'      => $utmSource,
    ':utm_medium'      => $utmMedium,
    ':utm_campaign'    => $utmCampaign,
    ':utm_content'     => $utmContent,
    ':utm_term'        => $utmTerm,
]);

echo json_encode([
    'ok' => true
]);

?>
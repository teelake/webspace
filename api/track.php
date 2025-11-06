<?php
// Basic visitor tracking endpoint
// Accepts JSON via POST and stores into MySQL

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	echo json_encode(['ok' => true]);
	exit;
}

require_once __DIR__ . '/../config/database.php';

function getClientIp(): string {
	$keys = [
		'HTTP_CF_CONNECTING_IP', // Cloudflare
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_REAL_IP',
		'REMOTE_ADDR'
	];
	foreach ($keys as $key) {
		if (!empty($_SERVER[$key])) {
			$ipList = explode(',', $_SERVER[$key]);
			return trim($ipList[0]);
		}
	}
	return '0.0.0.0';
}

function sanitize(?string $v): ?string {
	if ($v === null) return null;
	return trim(mb_substr($v, 0, 2000));
}

function parseReferrerDomain(?string $referrer): ?string {
	if (!$referrer) return null;
	$host = parse_url($referrer, PHP_URL_HOST);
	return $host ? strtolower($host) : null;
}

function detectSearchEngineAndTerm(?string $referrer): array {
	$engine = null; $term = null; $isOrganic = 0; $isPaid = 0;
	if (!$referrer) return [$engine, $term, $isOrganic, $isPaid];
	$host = strtolower(parse_url($referrer, PHP_URL_HOST) ?? '');
	parse_str(parse_url($referrer, PHP_URL_QUERY) ?? '', $q);
	$maps = [
		'google.' => ['param' => 'q', 'engine' => 'google'],
		'bing.' => ['param' => 'q', 'engine' => 'bing'],
		'yahoo.' => ['param' => 'p', 'engine' => 'yahoo'],
		'duckduckgo.' => ['param' => 'q', 'engine' => 'duckduckgo'],
		'yandex.' => ['param' => 'text', 'engine' => 'yandex'],
	];
	foreach ($maps as $needle => $meta) {
		if (strpos($host, $needle) !== false) {
			$engine = $meta['engine'];
			$term = isset($q[$meta['param']]) ? urldecode((string)$q[$meta['param']]) : null;
			$isOrganic = 1;
			break;
		}
	}
	// rudimentary paid detection via gclid/msclkid
	if ($engine === 'google' && (isset($q['gclid']) || isset($q['gclsrc']))) { $isPaid = 1; $isOrganic = 0; }
	if ($engine === 'bing' && isset($q['msclkid'])) { $isPaid = 1; $isOrganic = 0; }
	return [$engine, $term, $isOrganic, $isPaid];
}

function extractUtmParams(?string $pageUrl): array {
	$utm = ['utm_source'=>null,'utm_medium'=>null,'utm_campaign'=>null,'utm_term'=>null,'utm_content'=>null];
	if (!$pageUrl) return $utm;
	parse_str(parse_url($pageUrl, PHP_URL_QUERY) ?? '', $q);
	foreach ($utm as $k => $_) {
		if (isset($q[$k])) $utm[$k] = sanitize((string)$q[$k]);
	}
	return $utm;
}

// Read JSON body
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) {
	http_response_code(400);
	echo json_encode(['ok' => false, 'error' => 'Invalid JSON']);
	exit;
}

$conn = getDBConnection();
if (!$conn) {
	http_response_code(500);
	echo json_encode(['ok' => false, 'error' => 'DB connection failed']);
	exit;
}

$sessionId = sanitize($data['session_id'] ?? null) ?? bin2hex(random_bytes(16));
$userAgent = sanitize($_SERVER['HTTP_USER_AGENT'] ?? ($data['user_agent'] ?? ''));
$ip = getClientIp();

$deviceType = sanitize($data['device_type'] ?? null);
$deviceBrand = sanitize($data['device_brand'] ?? null);
$deviceModel = sanitize($data['device_model'] ?? null);
$osName = sanitize($data['os_name'] ?? null);
$osVersion = sanitize($data['os_version'] ?? null);
$browserName = sanitize($data['browser_name'] ?? null);
$browserVersion = sanitize($data['browser_version'] ?? null);
$screenRes = sanitize($data['screen_resolution'] ?? null);
$viewport = sanitize($data['viewport_size'] ?? null);
$isMobile = (int)($data['is_mobile'] ?? 0);
$isTablet = (int)($data['is_tablet'] ?? 0);
$isDesktop = (int)($data['is_desktop'] ?? 0);
$isBot = (int)($data['is_bot'] ?? 0);

$pageUrl = sanitize($data['page_url'] ?? null);
$pageTitle = sanitize($data['page_title'] ?? null);
$pagePath = sanitize(parse_url($pageUrl ?? '', PHP_URL_PATH) ?? null);

$referrer = sanitize($data['referrer_url'] ?? ($_SERVER['HTTP_REFERER'] ?? null));
$refDomain = parseReferrerDomain($referrer);
$isDirect = empty($referrer) ? 1 : 0;
[$searchEngine, $searchTerm, $isOrganic, $isPaid] = detectSearchEngineAndTerm($referrer);

$utm = extractUtmParams($pageUrl);

$timeOnPage = isset($data['time_on_page']) ? (int)$data['time_on_page'] : null;
$scrollDepth = isset($data['scroll_depth']) ? (int)$data['scroll_depth'] : null;

$visitDate = date('Y-m-d H:i:s');
$visitDateLocal = sanitize($data['visit_date_local'] ?? null);

$language = sanitize($data['language'] ?? null);
$acceptLanguage = sanitize($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? ($data['accept_language'] ?? null));
$connectionType = sanitize($data['connection_type'] ?? null);

// IP-based geolocation (best-effort; non-blocking) with provider fallbacks
$geo = ['country'=>null,'country_code'=>null,'region'=>null,'city'=>null,'latitude'=>null,'longitude'=>null,'timezone'=>null,'isp'=>null];
try {
    $ctx = stream_context_create(['http' => ['timeout' => 1.8]]);
    // Provider 1: ipapi.co
    $geoJson = @file_get_contents('https://ipapi.co/' . urlencode($ip) . '/json/', false, $ctx);
    if ($geoJson) {
        $g = json_decode($geoJson, true);
        if (is_array($g) && empty($g['error'])) {
            $geo['country'] = sanitize($g['country_name'] ?? null);
            $geo['country_code'] = sanitize($g['country'] ?? null);
            $geo['region'] = sanitize($g['region'] ?? null);
            $geo['city'] = sanitize($g['city'] ?? null);
            $geo['latitude'] = isset($g['latitude']) ? (string)$g['latitude'] : null;
            $geo['longitude'] = isset($g['longitude']) ? (string)$g['longitude'] : null;
            $geo['timezone'] = sanitize($g['timezone'] ?? null);
            $geo['isp'] = sanitize($g['org'] ?? null);
        }
    }
    // Provider 2: ipwho.is
    if (!$geo['country']) {
        $geoJson2 = @file_get_contents('https://ipwho.is/' . urlencode($ip), false, $ctx);
        if ($geoJson2) {
            $g2 = json_decode($geoJson2, true);
            if (is_array($g2) && ($g2['success'] ?? false)) {
                $geo['country'] = sanitize($g2['country'] ?? null);
                $geo['country_code'] = sanitize($g2['country_code'] ?? null);
                $geo['region'] = sanitize($g2['region'] ?? null);
                $geo['city'] = sanitize($g2['city'] ?? null);
                if (isset($g2['latitude'])) { $geo['latitude'] = (string)$g2['latitude']; }
                if (isset($g2['longitude'])) { $geo['longitude'] = (string)$g2['longitude']; }
                $geo['timezone'] = sanitize(($g2['timezone']['id'] ?? null) ?: ($g2['timezone'] ?? null));
                $geo['isp'] = sanitize($g2['connection']['isp'] ?? null);
            }
        }
    }
    // Provider 3: ip-api.com
    if (!$geo['country']) {
        $geoJson3 = @file_get_contents('http://ip-api.com/json/' . urlencode($ip) . '?fields=status,country,countryCode,regionName,city,lat,lon,timezone,isp', false, $ctx);
        if ($geoJson3) {
            $g3 = json_decode($geoJson3, true);
            if (is_array($g3) && ($g3['status'] ?? '') === 'success') {
                $geo['country'] = sanitize($g3['country'] ?? null);
                $geo['country_code'] = sanitize($g3['countryCode'] ?? null);
                $geo['region'] = sanitize($g3['regionName'] ?? null);
                $geo['city'] = sanitize($g3['city'] ?? null);
                if (isset($g3['lat'])) { $geo['latitude'] = (string)$g3['lat']; }
                if (isset($g3['lon'])) { $geo['longitude'] = (string)$g3['lon']; }
                $geo['timezone'] = sanitize($g3['timezone'] ?? null);
                $geo['isp'] = sanitize($g3['isp'] ?? null);
            }
        }
    }
} catch (Throwable $e) {
    // ignore network errors
}

$stmt = $conn->prepare(
    "INSERT INTO visitor_analytics (
        session_id, ip_address, user_agent,
        device_type, device_brand, device_model, os_name, os_version,
        browser_name, browser_version, screen_resolution, viewport_size,
        is_mobile, is_tablet, is_desktop, is_bot,
        country, country_code, region, city, latitude, longitude, timezone, isp,
        referrer_url, referrer_domain, is_direct,
        search_engine, search_term, is_organic, is_paid,
        utm_source, utm_medium, utm_campaign, utm_term, utm_content,
        page_url, page_title, page_path,
        time_on_page, scroll_depth,
        visit_date, visit_date_local,
        language, accept_language, connection_type
    ) VALUES (
        ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?, ?, ?,
        ?, ?,
        ?, ?,
        ?, ?, ?
    )"
);

if (!$stmt) {
	http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Prepare failed', 'db_error' => $conn->error]);
	closeDBConnection($conn);
	exit;
}

// Build params dynamically to ensure the count matches placeholders
$params = [
    $sessionId, $ip, $userAgent,
    $deviceType, $deviceBrand, $deviceModel, $osName, $osVersion,
    $browserName, $browserVersion, $screenRes, $viewport,
    (string)$isMobile, (string)$isTablet, (string)$isDesktop, (string)$isBot,
    $geo['country'], $geo['country_code'], $geo['region'], $geo['city'],
    (string)$geo['latitude'], (string)$geo['longitude'], $geo['timezone'], $geo['isp'],
    $referrer, $refDomain, (string)$isDirect,
    $searchEngine, $searchTerm, (string)$isOrganic, (string)$isPaid,
    $utm['utm_source'], $utm['utm_medium'], $utm['utm_campaign'], $utm['utm_term'], $utm['utm_content'],
    $pageUrl, $pageTitle, $pagePath,
    isset($timeOnPage) ? (string)$timeOnPage : null,
    isset($scrollDepth) ? (string)$scrollDepth : null,
    $visitDate, $visitDateLocal,
    $language, $acceptLanguage, $connectionType
];

$types = str_repeat('s', count($params));
$stmt->bind_param($types, ...$params);

$ok = $stmt->execute();
$stmt->close();
closeDBConnection($conn);

if (!$ok) {
	http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'Insert failed',
        'db_error' => $conn->error,
        'stmt_error' => $stmt->error
    ]);
	exit;
}

echo json_encode(['ok' => true]);

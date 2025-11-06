<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { echo json_encode(['ok' => true]); exit; }

require_once __DIR__ . '/../config/database.php';

function getIp(): string {
	foreach (['HTTP_CF_CONNECTING_IP','HTTP_X_FORWARDED_FOR','HTTP_X_REAL_IP','REMOTE_ADDR'] as $h) {
		if (!empty($_SERVER[$h])) { return trim(explode(',', $_SERVER[$h])[0]); }
	}
	return '0.0.0.0';
}

function s(?string $v): ?string { if ($v===null) return null; return trim(mb_substr($v, 0, 2000)); }

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) { http_response_code(400); echo json_encode(['ok'=>false,'error'=>'Invalid JSON']); exit; }

$conn = getDBConnection();
if (!$conn) { http_response_code(500); echo json_encode(['ok'=>false,'error'=>'DB connection failed']); exit; }

$sessionId = s($data['session_id'] ?? null);
$pageUrl = s($data['page_url'] ?? null);
$eventName = s($data['event_name'] ?? null);
$eventCategory = s($data['event_category'] ?? null);
$eventLabel = s($data['event_label'] ?? null);
$eventValue = isset($data['event_value']) ? (float)$data['event_value'] : null;
$meta = isset($data['meta']) ? json_encode($data['meta'], JSON_UNESCAPED_UNICODE) : null;
$referrer = s($data['referrer_url'] ?? ($_SERVER['HTTP_REFERER'] ?? null));
$visitDate = date('Y-m-d H:i:s');
$ip = getIp();
$ua = s($_SERVER['HTTP_USER_AGENT'] ?? ($data['user_agent'] ?? null));

if (!$sessionId || !$pageUrl || !$eventName) {
	http_response_code(400);
	echo json_encode(['ok'=>false,'error'=>'Missing required fields']);
	exit;
}

$stmt = $conn->prepare("INSERT INTO visitor_events (session_id,page_url,event_name,event_category,event_label,event_value,meta_json,referrer_url,visit_date,ip_address,user_agent) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
if (!$stmt) { http_response_code(500); echo json_encode(['ok'=>false,'error'=>'Prepare failed','db_error'=>$conn->error]); exit; }

$stmt->bind_param('ssssssdssss', $sessionId,$pageUrl,$eventName,$eventCategory,$eventLabel,$eventValue,$meta,$referrer,$visitDate,$ip,$ua);
$ok = $stmt->execute();
$stmt->close();
$conn->close();

if (!$ok) { http_response_code(500); echo json_encode(['ok'=>false,'error'=>'Insert failed','db_error'=>$conn->error]); exit; }

echo json_encode(['ok'=>true]);


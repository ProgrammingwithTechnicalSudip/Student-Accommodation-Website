<?php
session_start();
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['success'=>false,'message'=>'Please login to shortlist a property.']);
    exit;
}
$data=json_decode(file_get_contents('php://input'),true) ?? [];
$propertyId=(int)($data['property_id'] ?? 0);
$action=$data['action'] ?? 'toggle';

if ($propertyId <= 0) {
    http_response_code(422);
    echo json_encode(['success'=>false,'message'=>'Invalid property.']);
    exit;
}
$check=$pdo->prepare("SELECT id FROM properties WHERE id=?");
$check->execute([$propertyId]);
if (!$check->fetch()) {
    http_response_code(404);
    echo json_encode(['success'=>false,'message'=>'Property not found.']);
    exit;
}

$userId=(int)$_SESSION['user']['id'];
$exists=$pdo->prepare("SELECT 1 FROM interested_users WHERE user_id=? AND property_id=?");
$exists->execute([$userId,$propertyId]);
$isInterested=(bool)$exists->fetchColumn();

if ($action==='add' && !$isInterested) {
    $stmt=$pdo->prepare("INSERT INTO interested_users(user_id,property_id) VALUES(?,?)");
    $stmt->execute([$userId,$propertyId]);
    $isInterested=true;
} elseif ($action==='remove' && $isInterested) {
    $stmt=$pdo->prepare("DELETE FROM interested_users WHERE user_id=? AND property_id=?");
    $stmt->execute([$userId,$propertyId]);
    $isInterested=false;
} elseif ($action==='toggle') {
    if ($isInterested) {
        $stmt=$pdo->prepare("DELETE FROM interested_users WHERE user_id=? AND property_id=?");
        $stmt->execute([$userId,$propertyId]);
        $isInterested=false;
    } else {
        $stmt=$pdo->prepare("INSERT INTO interested_users(user_id,property_id) VALUES(?,?)");
        $stmt->execute([$userId,$propertyId]);
        $isInterested=true;
    }
}
echo json_encode(['success'=>true,'interested'=>$isInterested,'message'=>$isInterested?'Added to shortlist.':'Removed from shortlist.']);
?>

<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json; charset=utf-8');

$city = trim($_GET['city'] ?? '');
$gender = trim($_GET['gender'] ?? '');
$budget = (float)($_GET['budget'] ?? 0);

$sql = "SELECT p.*, 
        GROUP_CONCAT(a.name ORDER BY a.name SEPARATOR '||') AS amenities
        FROM properties p
        LEFT JOIN property_amenities pa ON pa.property_id=p.id
        LEFT JOIN amenities a ON a.id=pa.amenity_id
        WHERE 1=1";
$params=[];

if ($city !== '') { $sql .= " AND p.city = ?"; $params[]=$city; }
if (in_array($gender, ['Male','Female','Any'], true)) {
    $sql .= " AND (p.gender = ? OR p.gender = 'Any')";
    $params[]=$gender;
}
if ($budget > 0) { $sql .= " AND p.price <= ?"; $params[]=$budget; }

$sql .= " GROUP BY p.id ORDER BY p.rating DESC, p.id DESC";
$stmt=$pdo->prepare($sql);
$stmt->execute($params);
$rows=$stmt->fetchAll();

foreach ($rows as &$row) {
    $row['amenities'] = $row['amenities'] ? explode('||',$row['amenities']) : [];
}
echo json_encode(['success'=>true,'data'=>$rows]);
?>

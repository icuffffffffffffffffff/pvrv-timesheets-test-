<?php
require_once '_database.php';

if ($_GET["resource"]) {
    $stmt = $database->prepare('select * from events where resource_id = :resource and not ((event_end <= :start) or (event_start >= :end))');
    $stmt->bindParam(':resource', $_GET['resource']);
}
else {
    $stmt = $database->prepare('select * from events where not ((event_end <= :start) or (event_start >= : end))');
}
$stmt->bindParam(':start', $_GET['start']);
$stmt->bindParam(':end', $_GET['end']);
$stmt->execute();
$result = $stmt->fetchAll();

class Event {
    public $id, $text, $start, $end, $resource, $project, $bubbleHtml;
}
$events = array();

foreach($result as $row) {
    $e = new Event();
    $e->id = (int)$row['id'];
    $e->text = $row['name'];
    $e->start = $row['event_start'];
    $e->end = $row['event_end'];
    $e->resource = (int)$row['resource_id'];
    $e->project = (int)$row['project_id'];
    $e->bubbleHtml = "Details: <br/>".$e->text;
    $events[] = $e;
}

header('content-type: apps/json');
echo json_encode($events);

<?php
$key = $column['name'];
$code = $entry->$key;
$status = "";
switch ($code){
    case '0':
        $status = "Open";break;
    case '1':
        $status = "On Progress";break;
    case '2':
        $status = "Siap Dibayar";break;
    case '3':
        $status = "Finished";break;
}
?>
<td>{{str_limit(strip_tags($status), 80, "[...]") }}</td>
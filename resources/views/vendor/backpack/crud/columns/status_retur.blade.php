<?php
$key = $column['name'];
$code = $entry->pivot->{$key};
$status = "";
switch ($code){
    case '0':
        $status = "Cancel";break;
    case '1':
        $status = "Retur";break;
}
?>
<td>{{str_limit(strip_tags($status), 80, "[...]") }}</td>
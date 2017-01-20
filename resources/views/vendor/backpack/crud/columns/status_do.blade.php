<?php
$key = $column['name'];
$code = $entry->{$key};
$status = "";
switch ($code){
    case '0':
        $status = "Belum Terkirim";break;
    case '1':
        $status = "Terkirim";break;
}
?>
<td>{{str_limit(strip_tags($status), 80, "[...]") }}</td>
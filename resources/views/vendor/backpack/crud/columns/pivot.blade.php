<?php
    $key = $column['name'];
?>
<td>{{str_limit(strip_tags($entry->pivot->$key), 80, "[...]") }}</td>
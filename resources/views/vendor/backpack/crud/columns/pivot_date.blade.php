<?php
    $key = $column['name'];
?>
<td data-order="{{ $entry->pivot->$key }}">
    {{ Date::parse($entry->pivot->$key)->format(config('backpack.base.default_date_format')) }}
</td>
<td>
    <a class="btn btn-sm btn-{{$column['button'] or 'primary'}}"
       href="{{url($column['link'].'/'.$entry->id.'/'.$column['link_end'])}}">
        <i class="fa fa-{{$column['icon']}}"></i> {{$column['link_label'] or $column['label']}}
    </a>
</td>
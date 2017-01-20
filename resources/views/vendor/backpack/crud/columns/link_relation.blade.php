<td>
    <a class="btn btn-sm btn-{{$column['button'] or 'primary'}}"
       href="{{url($column['link'].'/'.$entry->{$column['relation']}->id.'/'.$column['link_end'])}}">
        <i class="fa fa-{{$column['icon']}}"></i> {{$column['link_label'] or ""}}
        {{$entry->{$column['relation']}->{$column['relation_label']} or $column['label']}}
    </a>
</td>
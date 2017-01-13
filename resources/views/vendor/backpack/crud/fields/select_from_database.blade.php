<!-- select -->
@php
    $options = [];
    foreach ($field['model']::all() as $m){
        $options[$m->{$field['value']}] = $m->{$field['display']};
    }
@endphp
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    <select
            name="{{ $field['name'] }}"
            @include('crud::inc.field_attributes')
    >

        @if (isset($field['allows_null']) && $field['allows_null']==true)
            <option value="">-</option>
        @endif

        @if (count($options))
            @foreach ($options as $key => $value)
                <option value="{{ $key }}"
                        @if ((isset($field['value']) && $key==$field['value']) || ( ! is_null( old($field['name']) ) && old($field['name']) == $key) )
                        selected
                        @endif
                >{{ $value }}</option>
            @endforeach
        @endif
    </select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
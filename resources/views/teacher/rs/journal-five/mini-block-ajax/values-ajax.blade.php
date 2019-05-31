@foreach($rs->valuerands as $value)
@if($value->type == 'value')
<span class="btn-val" id="{{$value->id}}" data-value="{{$value->value}}">
<span class="del-val" data-id="{{$value->id}}">&nbsp;</span>
{{$value->value}}
</span>
@endif
@endforeach

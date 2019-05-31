@foreach($rs->valuerands as $theme)
@if($theme->type == 'theme')
<span class="btn-theme" id="{{$theme->id}}" data-theme="{{$theme->value}}">
  <span class="del-theme" data-id="{{$theme->id}}">&nbsp;</span>
  {{$theme->value}}
</span>
@endif
@endforeach

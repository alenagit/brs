@if ($errors->has($name))
<span class="invalid-feedback" role="alert" style="display:block">
  <strong>{{ $errors->first($name) }}</strong>
</span>
@endif

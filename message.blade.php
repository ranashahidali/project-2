
@if (Session::has('error'))
	
@endif

<div class="alert alert-danger" role="alert">
	A simple danger alert—check it out!
	{{Session::get('error')}}
</div>


@if (Session::has('success'))

<div class="alert alert-success" role="alert">
	A simple success alert—check it out!
	{{Session::get('success')}}
  </div>
	
@endif

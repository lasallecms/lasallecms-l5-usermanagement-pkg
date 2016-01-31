@if (count($errors) > 0)
	<!-- Form Error List -->
	<div class="alert alert-danger">

		<!--
		<strong>Whoops! Something went wrong!</strong>

		<br><br>
		-->

		<ul style="list-style-type: none;">
			@foreach ($errors->all() as $error)
				<li><strong>{{ $error }}<strong></li>
			@endforeach
		</ul>
	</div>
@endif
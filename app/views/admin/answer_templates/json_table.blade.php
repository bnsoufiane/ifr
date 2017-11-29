<table style="width: auto; min-width: 20%; border-top: 1px solid #DADADA;">
	<tbody>
@foreach ($data->json as $key => $value)
	<tr>
		<td style="width: 50%;"><em>{{ $key }}:</em></td>
		<td>
			@if (empty($value))
			<span class="text-muted"><em>None</em></span>
			@else
			{{ $value }}
			@endif
		</td>
	</tr>
@endforeach
	</tbody>
</table>

@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('img/sohmc-logo-icon.jpg') }}" class="logo" alt="SOHMC Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>

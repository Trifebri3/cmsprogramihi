@extends('layouts.public.app')

@section('content')
@php
    $sections = $program->theme->layout_config['sections'] ?? [];
@endphp

<div class="space-y-0">
    @foreach($sections as $sec)
        @if($sec['is_visible'] ?? true)
            @include('program.components.' . $sec['type'], ['data' => $sec])
        @endif
    @endforeach
</div>
@endsection

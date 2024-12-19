@extends('layouts.app')

@section('title', 'Liked Post')

@section('content')
    @include('users.profile.header')

    {{-- Show all liked posts here --}}
    <div class="mt-5">
        @if ($liked_posts)
            <div class="row">
                @foreach ($liked_posts as $post)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <a href="{{ route('post.show', $post->id) }}">
                            <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="grid-img">
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <h3 class="text-muted text-center">No Posts Yet</h3>
        @endif
    </div>
@endsection
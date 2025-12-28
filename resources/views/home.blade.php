@extends('layouts.app')
@section('title', 'Home')

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')
    @include('partials.main-content')

    
    @if($latestArticles->count() > 0)
    <div class="articles bg-[#F0F0F0] my-5 flex flex-col gap-3 p-2 md:p-3">
       @foreach($latestArticles as $article)
            <x-article :article="$article" />
        @endforeach
    </div>
    @endif
@endsection
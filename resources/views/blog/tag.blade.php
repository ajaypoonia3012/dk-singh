@extends('layouts.app')

@section('title', $tag->name)
@section('meta_title', $tag->name . ' Fitness Articles | DK Singh Fitness')
@section('meta_description', 'Browse fitness articles tagged ' . $tag->name . ' from DK Singh Fitness.')
@section('canonical', route('blog.tag', $tag->slug))

@section('content')
    @include('blog.partials.archive', [
        'eyebrow' => 'Topic',
        'heading' => '#' . $tag->name,
        'description' => 'Practical guidance and expert articles about ' . $tag->name . '.',
    ])
@endsection

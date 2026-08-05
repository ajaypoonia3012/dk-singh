@extends('layouts.app')

@section('title', $category->seo_title ?: $category->name)
@section('meta_title', $category->seo_title ?: $category->name . ' Fitness Articles')
@section('meta_description', $category->seo_description ?: ($category->description ?: 'Explore ' . $category->name . ' articles from DK Singh Fitness.'))
@section('canonical', route('blog.category', $category->slug))

@section('content')
    @include('blog.partials.archive', [
        'eyebrow' => 'Category',
        'heading' => $category->name,
        'description' => $category->description,
    ])
@endsection

@extends('front.main_layout')
@section('meta')
    <meta property="og:image" content="{{ url("/") . '/' .  $ogImage }}" />
@endsection
@section('subview')

    @include("front.subviews.brokers.item")

@endsection

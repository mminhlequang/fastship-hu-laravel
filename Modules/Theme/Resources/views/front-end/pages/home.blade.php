@extends('theme::front-end.master')

@section('content')
    <main>
        @include('theme::front-end.home.banner')
        @include('theme::front-end.home.popular')
        @include('theme::front-end.home.fastest')
        @include('theme::front-end.home.discount')
        @include('theme::front-end.home.rate')
        @include('theme::front-end.home.favorite')
        @include('theme::front-end.home.partner')
        @include('theme::front-end.home.work')
        @include('theme::front-end.home.download')
        @include('theme::front-end.home.customer')
        @include('theme::front-end.home.order')
        @include('theme::front-end.home.blog')
    </main>
@endsection
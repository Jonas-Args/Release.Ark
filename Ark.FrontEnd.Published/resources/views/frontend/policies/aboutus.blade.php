@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class=" bg-white">
						<img style="margin:auto; margin-right:auto; display:block; width:100%" src="{{ asset('uploads/brands/ABOUT_ARK.png') }}" alt="About Us Photo" />
                        @php
                         //   echo \App\Policy::where('name', 'terms')->first()->content;
                        @endphp
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

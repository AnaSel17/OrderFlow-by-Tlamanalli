@extends('layouts.app')

@section('content')
<div class="container tonalli-bg" style="min-height: 100vh; padding-top: 40px;">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card" style="border-radius:20px;">
                <div class="card-header" style="background:var(--ton-gold); color:white; font-weight:600;">
                    {{ __('Dashboard') }}
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p style="color:#4b3f35; font-size:1.1rem; margin-bottom:0;">
                        {{ __('Bienvenido ya estás dentro!') }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

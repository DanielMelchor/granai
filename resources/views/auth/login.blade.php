@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-10 offset-md-1">
                            <img src="{{ asset('logos/grupo_amad_02.png')}}" style="text-align: center;">
                        </div>
                    </div>
                    <br>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group row">
                            <!--<label for="username" class="col-md-4 col-form-label text-md-right">Usuario</label>-->
                            <!--<label for="username" class="col-md-1 col-form-label text-md-right">{{ __('user') }}</label>-->

                            <div class="col-md-10 offset-md-1">
                                <input id="username" type="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" placeholder="Usuario" autofocus>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <!--<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>-->

                            <div class="col-md-10 offset-md-1">
                                <!--<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>-->
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="contraseña" autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-10 offset-md-1 text-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        <!--{{ __('Remember Me') }}-->
                                        {{ __('Recordarme en este equipo') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-10 offset-md-1">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Login') }}
                                    <!--{{ __('Ingresar') }}-->
                                </button>

                                <!--@if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif-->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

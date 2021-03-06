@extends('layouts.app')

@section('page-title', 'Masuk')

@section('content')
    <div class="container align-items-center">
        <div class="row justify-content-center">
            <div class="col-md-2 mb-2 mr-2 align-items-center">
                <div class="row align-items-center">
                    <picture class="mx-auto my-auto">
                        <source media="(max-width: 768px)" srcset="{{ asset('storage/uinsgd-logo.png') }}" style="height: auto; max-width: 10%;" >
                        <img src="{{ asset('storage/uin-bandung-logo.png') }}" alt="Logo UIN Sunan Gunung Djati Bandung" style="height: auto; max-width: 100%;" >
                    </picture>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Log In</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <fieldset>
                                <div class="form-group">
                                    <input id="identity" class="form-control @error('identity') is-invalid @enderror" placeholder="NIP" name="identity" type="text" autofocus required autocomplete>

                                    @error('identity')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input class="form-control @error('password') is-invalid @enderror" placeholder="Password" type="password" name="password" required autocomplete>
                                    
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" value="Remember Me"><span class="mx-2">Ingat Saya</span>
                                    </label>
                                </div>
                                <input class="btn btn-primary" type="submit" value="Login">
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
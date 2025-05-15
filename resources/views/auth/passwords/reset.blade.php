@extends('layouts.common_home')
@section('content')

    <div class="container container-fixed-lg">
        <div class="row justify-content-center bravo-login-form-page bravo-login-page">
            <div class="col-md-5">
                <div class="">
                    <h4 class="form-title">Reset Password</h4>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                    <form autocomplete="off" class="form bravo-forms-register themeForm" method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                       
                       
                        <div class="form-group">
                            <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Confirm Your Email
                                <span class=" required">*</span></label>
                            <input autocomplete="off" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                name="email" value="{{ old('email') }}" required placeholder="Confirm Your Email">
                            <i class="input-icon field-icon icofont-mail"></i>
                            <span class="invalid-feedback error error-email"></span>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group password">
                                    <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Password
                                        <span class=" required">*</span></label>
                                    <input type="password" class="form-control" name="password" autocomplete="off"
                                        placeholder="Password">
                                    <i class="input-icon field-icon icofont-ui-password"></i>
                                    <a href="javascript:;" class="togglePassField"><i class="input-icon icofont-eye"></i></a>
                                    <span class="invalid-feedback error error-password"></span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group password">
                                    <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Confirm Password
                                        <span class=" required">*</span></label>
                                    <input type="password" class="form-control" name="password_confirmation" autocomplete="off"
                                        placeholder="Confirm Password">
                                    <i class="input-icon field-icon icofont-ui-password"></i>
                                    <a href="javascript:;" class="togglePassField"><i class="input-icon icofont-eye"></i></a>
                                    <span class="invalid-feedback error error-password"></span>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <button type="submit" class="btn btn-primary form-submit">
                                Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

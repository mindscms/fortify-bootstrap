<div class="container pt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Two Factor Authentication') }}</div>

                <div class="card-body">
                    @if (!auth()->user()->two_factor_secret)
                        <div class="text-muted m-3">
                            <h5>{{ __('You have not enabled two factor authentication.') }}</h5>
                            {{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.') }}
                        </div>
                        <form method="post" action="{{ url('user/two-factor-authentication') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-block">{{ __('Enable Two-Factor') }}</button>
                        </form>
                    @else
                        <div class="text-muted m-3">
                            <h5>{{ __('You have enabled two factor authentication.') }}</h5>
                            <p>{{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.') }}</p>

                            <p>{{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.') }}</p>

                        </div>

                        <div class="border border-primary rounded text-center mb-3 p-3">
                            {!! auth()->user()->twoFactorQrCodeSvg() !!}
                        </div>

                        <div class="text-muted m-3">
                            <p> {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}</p>
                        </div>



                        <div class="border border-primary rounded text-center mb-3 p-3">
                            @foreach(json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                                <div class="text-muted">{{ $code }}</div>
                            @endforeach
                        </div>


                        <a href="javascript:void(0);" class="btn btn-primary" onclick="document.getElementById('regenerate_two_factor_auth').submit();">{{ __('Regenerate Recovery codes') }}</a>
                        <a href="javascript:void(0);" class="btn btn-danger" onclick="document.getElementById('delete_two_factor_auth').submit();">{{ __('Disable Two-Factor') }}</a>

                        <form method="post" action="{{ url('user/two-factor-recovery-codes') }}" id="regenerate_two_factor_auth" style="display: none">@csrf</form>
                        <form method="post" action="{{ url('user/two-factor-authentication') }}" id="delete_two_factor_auth" style="display: none">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

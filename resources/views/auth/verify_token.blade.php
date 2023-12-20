@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('token-verification-post') }}">
    {{ csrf_field() }}
    <input type="hidden" name="user_email" value="{{ $user_email }}">
    <input type="hidden" name="session_token" value="{{ $session_token }}">

    <h1>Verify Token</h1>
    <p>We have sent you an email with a token.</p>
    <p>Enter the token you received in your email.</p>
    <p>If you did not receive the email, click the resend button below.</p>
    <p>Your email: {{ $user_email }}</p>
    <label for="token">Token</label>
    <input id="token" type="text" name="token" value="{{ old('token') }}" required autofocus>
    <div class="alert alert-success mt-3" style="display: none;"></div>
    <div class="alert alert-danger mt-3" style="display: none;"></div>

    @if ($errors->any())
        <div id="errorPopup" class="popup-message alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
    @if(session('status') )
        <div class="message">{{ session('status') }}</div>
        <div class="info">Please check your email for the token.</div>
    @endif
    <button type="submit">
        Verify token
    </button> 
    <p>Didn't receive the token?</p>
    <button type="button" id="resend-email">
        <span id="resend-spinner" style="display: none;">Processing...</span>
        Resend email
    </button>
    <script>
        var userEmail = '{{ $user_email }}';
        var resendButton = document.getElementById("resend-email");
        var resendSpinner = document.getElementById("resend-spinner");
        document.getElementById("resend-email").onclick = function() {
            if (!userEmail) {
                console.error("Invalid email address");
                return;
            }
            resendButton.disabled = true;
            resendSpinner.style.display = 'inline';
            sendAjaxRequest("post","/token-verification-resend",{
                user_email: userEmail,
                _token: '{{ csrf_token() }}'
            }, function() {
                try{
                    //console.log("Response text:", this.responseText);
                    const response = JSON.parse(this.responseText);
                    const successMessage = document.querySelector('.alert-success');
                    const errorMessage = document.querySelector('.alert-danger');

                    if (response.success) {
                        // Display success message
                        successMessage.innerText = response.message;
                        successMessage.style.display = 'block';

                        // Clear any existing error message
                        errorMessage.style.display = 'none';
                    } else {
                        // Display error message
                        errorMessage.innerText = response.message;
                        errorMessage.style.display = 'block';

                        // Clear any existing success message
                        successMessage.style.display = 'none';
                }}catch(e){
                    console.log(e);
                }finally {
                // Hide loading spinner
                resendButton.disabled = false;
                resendSpinner.style.display = 'none';
            }
        });
        }
    </script>
</form>
@endsection

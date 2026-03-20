<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to PayU...</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
        }
        .loader-container {
            text-align: center;
        }
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        h2 { color: #333; font-weight: 400; }
        p { color: #666; }
    </style>
</head>
<body onload="document.getElementById('payu_form').submit();">
    <div class="loader-container">
        <div class="loader"></div>
        <h2>Secure Payment Redirection</h2>
        <p>Please wait while we redirect you to the PayU payment gateway...</p>
        <p>Do not refresh the page or click the back button.</p>

        <form action="{{ $data['action'] }}" method="POST" id="payu_form" style="display: none;">
            <input type="hidden" name="key" value="{{ $data['key'] }}" />
            <input type="hidden" name="txnid" value="{{ $data['txnid'] }}" />
            <input type="hidden" name="amount" value="{{ $data['amount'] }}" />
            <input type="hidden" name="productinfo" value="{{ $data['productinfo'] }}" />
            <input type="hidden" name="firstname" value="{{ $data['firstname'] }}" />
            <input type="hidden" name="email" value="{{ $data['email'] }}" />
            <input type="hidden" name="phone" value="{{ $data['phone'] }}" />
            <input type="hidden" name="surl" value="{{ $data['surl'] }}" />
            <input type="hidden" name="furl" value="{{ $data['furl'] }}" />
            <input type="hidden" name="hash" value="{{ $data['hash'] }}" />
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>

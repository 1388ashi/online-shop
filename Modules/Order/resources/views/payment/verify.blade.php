<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>{{ config('app.name') }}</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <style>
        /* @font-face {
            font-family: 'sans_bold';
            src:url('/assets/fonts/fonts/IRANSansWeb(FaNum)_Bold.eot?#') format('eot'),
            url('/assets/fonts/fonts/IRANSansWeb(FaNum)_Bold.woff') format('woff'),
            url('/assets/fonts/fonts/IRANSansWeb(FaNum)_Bold.ttf') format('truetype');
        } */

        body {
            padding-top: 5rem;
            font-family: 'sans_bold', serif;
        }
        .container {
            direction: rtl;
            text-align: right;
        }
    </style>
</head>

<body>

@if($status == 'success')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="box-center text-center">
                    <h1>{{ $message }}</h1>
                    <p class="alert alert-success">مبلغ : {{ number_format($payment->amount) }} تومان</p>
                    <a href="{{ config('app.front_url') }}" class="btn btn-danger text-center align-item-center">بازگشت به سایت</a>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="container c2">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="box-center2 text-center">
                    <h1>پرداخت انجام نشد</h1>
                    <p class="alert alert-danger">{{ $message }}</p>
                    <a href="{{ config('app.front_url') }}" class="btn btn-danger text-center align-item-center">بازگشت به سایت</a>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="https://maxcdn.bootstrapcdn.com/js/ie10-viewport-bug-workaround.js"></script>
<!-- Holder.js for placeholder images -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/holder/2.9.4/holder.min.js"></script>
</body>
</html>

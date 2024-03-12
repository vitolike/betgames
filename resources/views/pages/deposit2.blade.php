<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Depósito de Saldo</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <style>
        body {
            background-image: url("/img/bgpagamento.svg");

        }

        .card {
            width: 500px;
            border-radius: 35px;
            background-color: #fff;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)
        }

        .total-amount {
            font-size: 22px;
            font-weight: 700;
            color: #383737
        }

        .amount-container {
            background-color: #e9eaeb;
            padding: 6px;
            padding-left: 15px;
            padding-right: 15px;
            border-radius: 8px
        }

        .amount-text {
            font-size: 20px;
            font-weight: 700;
            color: #4B0082
        }

        .dollar-sign {
            font-size: 20px;
            font-weight: 700;
            color: #000
        }

        .label-text {
            font-size: 16px;
            font-weight: 600;
            color: #b2b2b2
        }

        .credit-card-number {
            z-index: 28;
            border: 2px solid #ced4da;
            border-radius: 6px;
            font-weight: 600
        }

        .credit-card-number:focus {
            box-shadow: none;
            border: 2px solid #18dd30
        }

        .visa-icon {
            position: relative;
            top: 42px;
            right: 14px;
            z-index: 30
        }

        .expiry-class {
            width: 120px;
            border: 2px solid #ced4da;
            font-weight: 600;
            font-size: 12px;
            height: 48px
        }

        .expiry-class:focus {
            box-shadow: none;
            border: 2px solid #18dd30
        }

        .cvv-class {
            width: 120px;
            border: 2px solid #ced4da;
            font-weight: 600;
            font-size: 12px;
            height: 48px
        }

        .cvv-class:focus {
            box-shadow: none;
            border: 2px solid #18dd30
        }

        .payment-btn {
            background-image: linear-gradient(45deg, #4B0082 0%, #6304ac 51%, #4B0082 100%);
            padding: 15px;
            padding-left: 25px;
            padding-right: 25px;
            color: #fff;
            font-weight: 600;
            border-radius: 12px
        }

        .payment-btn:hover {
            box-shadow: none;
            color: #fff
        }

        .cancel-btn {
            background-color: #fff;
            color: #b2b2b2;
            padding: 0px;
            padding-top: 3px;
            padding-bottom: 3px;
            font-weight: 600;
            border-radius: 6px
        }

        .cancel-btn:hover {
            border: 2px solid #b2b2b2;
            color: #b2b2b2
        }

        .cancel-btn:focus {
            box-shadow: none
        }

        .label-text-cc-number {
            position: relative;
            top: 4px
        }

        .copy-tooltip {
            pointer-events: none;
            position: absolute;
            bottom: 100%;
            left: 50%;
            background: rgba(97,105,145,.2);
            border-radius: 6px;
            margin-bottom: 6px;
            padding: 5px 15px;
            font-size: 11px;
            text-align: center;
            -webkit-transform: translate3d(-50%,30px,0);
            transform: translate3d(-50%,30px,0);
            opacity: 0;
        }

        .copy-tooltip.visible {
            -webkit-animation: floatup 1s 1;
            animation: floatup 1s 1
        }
    </style>

    <script type="text/javascript" src="https://www.webtoolkitonline.com/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="https://www.webtoolkitonline.com/js/jquery.qrcode-0.6.0.min.js"></script>

</head>
<body>

<form>
<div class="container mt-5 d-flex justify-content-center">
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="total-amount">Total de Depósito</h5>
            <div class="amount-container"><span class="amount-text"><span class="dollar-sign">R$ </span>{{$payment['sum']}}</span></div>
        </div>



        <div class="mt-3 mb-4 text-center">
            <div id="qrcode" class=""></div>
        </div>

        <div class="">
            <label class="d-flex justify-content-between"> <span class="label-text label-text-cc-number">COPIA & COLA</span>
                <img src="https://i.imgur.com/qw5Wi6W.png" width="30" class="visa-icon"/>
            </label>
            <input type="text" value="{{$payment['extra_info']}}" id="qrcmess" name="qrcmess" class="form-control credit-card-number" placeholder="Copia & Cola PIX">
        </div>

        <button type="button" class="btn" id="generate"></button>

        <div class="d-flex justify-content-between pt-5 align-items-center">
            <a href="/" class="btn cancel-btn">Paguei e Voltar</a>
            <div>
                <button type="button" class="btn payment-btn" onclick="copyToClipboard('#qrcmess')">Copiar Copia&Cola</button>
                <div class="copy-tooltip"><span>COPIADO COM SUCESSO!</span></div>
            </div>
        </div>

    </div>
</div>
</form>


<script type="text/javascript">
    $(document).ready(function () {
        $("#generate").click();
    });


    function copyToClipboard(element) {
        var $temp = $('<input>'),
            clear;
        $('.copy-tooltip').removeClass('visible');
        clearInterval(clear);
        $('body').append($temp);
        $temp.val($(element).val()).select();
        document.execCommand('copy');
        $temp.remove();
        $('.copy-tooltip').addClass('visible');
        clear = setInterval(function () {
            clearInterval(clear);
            $('.copy-tooltip').removeClass('visible');
        }, 1000)
    }


    $("#generate").click(
        function() {
            $("#qrcode").empty();
            $("#qrcode").qrcode({
                render : 'image',
                color: 'black',
                text: $( "#qrcmess").val()
            });
        }
    );

</script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">

</body>
</html>

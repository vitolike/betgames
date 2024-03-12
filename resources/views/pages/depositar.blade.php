@extends('layout')

@section('content')
<link rel="stylesheet" href="/css/affiliate.css">
<div class="section">
    <div class="section-page">
        <div class="quest-banner affiliate">
            <div class="caption">
                <h1><span>Realize o seu pagamento</span></h1>
        <div class="affiliate-stats">
            <div class="left">
                </div>
            </div>
            <div class="right">
                <div class="affiliate-stats-item full">
                    <div class="wrap">
                        <div class="block">
                            <svg class="icon icon-coin balance bonus">
                                <use xlink:href="/img/symbols.svg#icon-coin"></use>
                            </svg>
                            <div class="num">{{$u->ref_money}}</div>
                            <div class="text">Ganhos Disponíveis</div>
                            <span id="withdraw-button" class="" data-toggle="tooltip" data-placement="top" title="Quantidade mínima
 para retirada {{ $settings->min_ref_withdraw }} R$ 10,00">
                                    <div class="caption">
                                        <button type="button" {{ $u->ref_money < $settings->min_ref_withdraw  ? 'disabled' : '' }} class="btn">Retirar</button></span>
                                    </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

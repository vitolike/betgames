@extends('layout')

@section('content')
    <link rel="stylesheet" href="/css/affiliate.css">
    <link rel="stylesheet" href="/css/deposito.css">

    <script type="text/javascript" src="https://www.webtoolkitonline.com/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="https://www.webtoolkitonline.com/js/jquery.qrcode-0.6.0.min.js"></script>


    <div class="modal fade" id="walletModal" tabindex="-1" role="dialog"
                     aria-labelledby="walletModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog deposit-modal modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <button class="modal-close" data-dismiss="modal" aria-label="Close">
                                <svg class="icon icon-close">
                                    <use xlink:href="/img/symbols.svg#icon-close"></use>
                                </svg>
                            </button>
                            <div class="deposit-modal-component">
                                <div class="wrap">
                                    <div class="tabs">
                                        <button type="button" class="btn btn-tab isActive">Depositar</button>
                                        <button type="button" class="btn btn-tab">Sacar</button>
                                    </div>
                                    <div class="deposit-section tab active" data-type="deposite">
                                        <form action="/pay" method="post" id="payment">
                                            @if($settings->dep_bonus_min > 0)
                                                <div class="form-row">
                                                    <label>

                                                    </label>
                                                </div>
                                            @endif

                                            <div class="form-row">
                                                <label>
                                                    <div class="form-label">Valor para depositar</div>
                                                    <div class="form-field">
                                                        <div class="input-valid">
                                                            <input class="input-field input-with-icon" name="amount"
                                                                   value="{{$settings->min_dep}}"
                                                                   placeholder="">
                                                            <div class="input-icon">
                                                            </div>
                                                            <div class="valid inline"></div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-label">Método de Pagamento</div>
                                                <div class="select-payment">
                                                    <input type="hidden" name="type" value="" id="depositType">

                                                    <div class="bottom-start dropdown">

                                                        <button type="button" aria-haspopup="true"
                                                                aria-expanded="false"
                                                                class="dropdown-toggle btn btn-secondary"
                                                                id="dropdownMenuButton" data-toggle="dropdown"
                                                                aria-haspopup="true" aria-expanded="false">
                                                            Selecione um método de pagamento
                                                            <div class="opener">
                                                                <svg class="icon icon-down">
                                                                    <use
                                                                        xlink:href="{{ url('/') }}/img/symbols.svg#icon-down"></use>
                                                                </svg>
                                                            </div>
                                                        </button>

                                                        <div tabindex="-1" role="menu" aria-hidden="true"
                                                             class="dropdown-menu" x-placement="bottom-start"
                                                             data-placement="bottom-start">

                                                            @foreach($payment_provider as $pp)
                                                                <button type="button" data-id="5" tabindex="0"
                                                                        role="menuitem" class="dropdown-item"
                                                                        data-system="{{$pp->provider_name}}">
                                                                    <div class="image"><img
                                                                            src="{{ url('/') }}/img/wallets/pix.svg"
                                                                            alt="{{$pp->payment_methods}} do {{$pp->payment_methods}}">
                                                                    </div>
                                                                    <span>{{$pp->payment_methods}}</span>
                                                                </button>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row" {{$u->cpf == NULL ? '' : 'hidden'}}>
                                                <label>
                                                    <div class="form-label">CPF do Pagador</div>
                                                    <div class="form-field">
                                                        <div class="input-valid">
                                                            <input class="input-field input-with-icon" name="cpf"
                                                                   value="{{$u->cpf}}"
                                                                   placeholder="CPF do pagador">
                                                            <div class="valid inline"></div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>

                                            <button type="submit" class="btn btn-green">Depositar</button>
                                        </form>
                                    </div>
                                    <div class="deposit-section tab" data-type="withdraw">
                                        <div class="form-row">
                                            <label>
                                                <div class="form-label">Valor disponível para saque</div>
                                                <div class="form-field">
                                                    <div class="input-valid">
                                                        <input class="input-field input-with-icon"
                                                               value="{{$u->requery}}" readonly>
                                                        <div class="input-icon">
                                                        </div>
                                                        <small class="text-warning">Obs: Seu valor de saque é o
                                                            valor de
                                                            lucros.</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-label">Metodo de Saque</div>
                                            <div class="select-payment">
                                                <input type="hidden" name="type" value="" id="withdrawType">
                                                <div class="bottom-start dropdown">
                                                    <button type="button" aria-haspopup="true" aria-expanded="false"
                                                            class="dropdown-toggle btn btn-secondary"
                                                            id="dropdownMenuButton" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                        Selecione um Método de Saque
                                                        <div class="opener">
                                                            <svg class="icon icon-down">
                                                                <use xlink:href="/img/symbols.svg#icon-down"></use>
                                                            </svg>
                                                        </div>
                                                    </button>
                                                    <div tabindex="-1" role="menu" aria-hidden="true"
                                                         class="dropdown-menu" x-placement="bottom-start"
                                                         style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 46px, 0px);"
                                                         data-placement="bottom-start">
                                                        <button type="button" data-id="6" tabindex="0"
                                                                role="menuitem"
                                                                class="dropdown-item" data-system="pix">
                                                            <div class="image"><img
                                                                    src="{{ url('/') }}/img/wallets/pix.svg"
                                                                    alt="pix"></div>
                                                            <span>Pix</span>
                                                        </button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="form-row">
                                                <label>
                                                    <div class="form-label">Digite o valor para Sacar (BRL)</div>
                                                    <div class="form-field">
                                                        <div class="input-valid">
                                                            <input class="input-field input-with-icon" name="amount"
                                                                   value="" id="valwithdraw"
                                                                   placeholder="Digite um Valor">
                                                            <div class="input-icon">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label>
                                                <div class="form-label">
                                                    @if($u->cpf == NULL)
                                                        Digite sua Chave PIX
                                                    @else
                                                        Sua chave PIX cadastrada
                                                    @endif
                                                </div>
                                                <div class="form-field">
                                                    <div class="input-valid">
                                                        <input class="input-field"
                                                               {{$u->cpf != NULL ? 'readonly' : ''}} name="purse"
                                                               placeholder=""
                                                               value="{{$u->cpf != NULL ? $u->cpf : ''}}"
                                                               id="numwallet">
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        <button type="submit" disabled="" class="btn btn-green" id="submitwithdraw">
                                            Retirar
                                        </button>
                                        <div class="checkbox-block">
                                            <label>Aceito os termos de transferências.
                                                <input name="agree" type="checkbox" id="withdraw-checkbox"
                                                       value=""><span class="checkmark"></span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

@endsection

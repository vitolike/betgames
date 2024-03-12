@extends('admin')

@section('content')
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">CONFIGURAÇÕES | PROVEDORES DE PAGAMENTOS</h3>
        </div>
    </div>

    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

        <a data-toggle="modal" href="#newPaymentProvider" class="btn btn-primary btn-elevate btn-icon-sm">
            <i class="la la-plus"></i>
            Adicionar novo método
        </a>

        <div class="modal fade" id="newPaymentProvider" tabindex="-1" role="dialog" aria-labelledby="newLabel"
             style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Adicionar novo provedor de pagamento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="kt-form-new" method="post" action="/admin/payment_gateways/new/save" id="save">
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="name">Tipo de Pagamento</label>
                                <input type="text" class="form-control"
                                       placeholder="PIX, Credit Card, Crypto, Debig, PayPal Wallet ...."
                                       name="payment_methods" id="url">
                            </div>

                            <div class="form-group">
                                <label for="name">Nome do Provedor</label>
                                <input type="text" class="form-control"
                                       placeholder="MercadoPago, PayPal, PicPay, PagSeguro ...."
                                       name="provider_name" id="url">
                            </div>

                            <div class="form-group">
                                <label for="name">Status do Provedor</label>
                                <select class="form-control" name="provider_status">
                                    <option value="0">Desativado</option>
                                    <option value="1">Ativado</option>
                                </select>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
                            <button type="submit" class="btn btn-primary">Adicionar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br><br>

        <div class="kt-portlet kt-portlet--tabs">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-toolbar">
                    <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right"
                        role="tablist">

                        @foreach($payments_provider_all as $provider)
                            <li class="nav-item">
                                <a class="nav-link {{ (Request::is('admin/payment_gateways#'.$provider->provider_name) ? 'active' : '') }}"
                                   data-toggle="tab" href="#{{$provider->provider_name}}" role="tab"
                                   aria-selected="true">
                                    @if($provider->provider_status == 0)
                                        🔒
                                    @endif {{ $provider->provider_name }} ({{ $provider->payment_methods }})
                                </a>
                            </li>
                        @endforeach

                    </ul>
                </div>
            </div>

            <form class="kt-form" method="post" action="/admin/payment_gateways/{{$provider->provider_name}}/save">
                <div class="kt-portlet__body">
                    <div class="tab-content">

                        @foreach($payments_provider_all as $provider)
                            <div
                                class="tab-pane {{ (Request::is('admin/payment_gateways#'.$provider->provider_name) ? 'active' : '') }}"
                                id="{{$provider->provider_name}}" role="tabpanel">
                                <div class="kt-section">

                                    @if($provider->provider_status == 0)
                                        <h2 class="text-lg-center text-danger">ESTE PROVEDOR ESTÁ DESATIVADO!</h2>
                                        <h5 class="text-md-center text-danger">Habilite este provedor para mostrar ao
                                            usuário final esta forma de pagamento.</h5>
                                    @endif

                                    @if($provider->provider_mode == 0)
                                        <h2 class="text-lg-center text-warning">ESTE PROVEDOR ESTÁ EM MODO DE
                                            HOMOLOGAÇÃO!</h2>
                                        <h5 class="text-md-center text-warning">Habilite modo LIVE deste provedor para
                                            validar pagamentos ao
                                            usuário final esta forma de pagamento.</h5>
                                    @endif

                                    <h3 class="kt-section__title">
                                        Configurações:
                                    </h3>

                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label>Tipo de Pagamento:</label>
                                            <input type="text" class="form-control"
                                                   placeholder="PIX, Cartão de Crédito, TED..."
                                                   value="{{$provider->payment_methods}}" name="payment_methods">
                                            <small class="text-info">* Mostrar ao usuário uma o mais forma de
                                                pagamento.</small>
                                        </div>
                                        <div class="col-lg-4">
                                            <label>Nome do Provedor:</label>
                                            <input type="text" class="form-control"
                                                   placeholder="PayPal, Skrill, MercadoPago, PagSeguro, PicPay..."
                                                   value="{{$provider->provider_name}}" name="provider_name">
                                            <small class="text-danger">* Não adicione acentos, espaços e
                                                símbolo.</small>
                                        </div>
                                        <div class="col-lg-4">
                                            <label>URL de Retorno (IPN/WebHook):</label>
                                            <input type="text" class="form-control"
                                                   placeholder="https://nome_do_site.com/api/payments/return"
                                                   @if($provider->url_return_ipn === NULL)
                                                       value="https://{{$_SERVER['HTTP_HOST']}}/api/payments/return"
                                                   name="url_return_ipn"
                                                   @else
                                                       disabled
                                                   value="{{$provider->url_return_ipn}}"
                                                @endif >
                                            <small class="text-warning">* Deve definir esta URL em seu retorno no
                                                provedor
                                                de pagamentos.</small>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label>Status deste provedor:</label>
                                            <select class="form-control" name="provider_status">
                                                <option
                                                    value="0" {{ ($provider->provider_status == 0) ? 'selected' : '' }}>
                                                    Desativado - esconder método de pagamento
                                                </option>
                                                <option
                                                    value="1" {{ ($provider->provider_status == 1) ? 'selected' : '' }}>
                                                    Ativado - mostrar método de pagamento
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <label>Habilitar modo Homologação:</label>
                                            <select class="form-control" name="provider_mode">
                                                <option
                                                    value="0" {{ ($provider->provider_mode == 0) ? 'selected' : '' }}>
                                                    Desativado - ativar modo de pagamento em HOMOLOGAÇÃO
                                                </option>
                                                <option
                                                    value="1" {{ ($provider->provider_mode == 1) ? 'selected' : '' }}>
                                                    Ativado - ativar modo de pagamento em LIVE
                                                </option>
                                            </select>
                                            <small class="text-warning">* Após testar, desative o modo
                                                Homologação.</small>
                                        </div>
                                    </div>

                                    <h3 class="kt-section__title">
                                        Credênciais [LIVE]:
                                    </h3>
                                    <div class="mb-3">
                                        <small class="text-info">* <a
                                                href="https://www.mercadopago.com.br/developers/panel"
                                                target="_blank" class="text-warning">Clique aqui</a>
                                            para obter suas chaves para o MercadoPago.
                                            <br> Obs¹: Para pagamentos PIX será necessário ter uma chave PIX cadastrada
                                            em
                                            sua conta MercadoPago (podendo ser até mesmo chave aleatória). </small>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label>Chave do Cliente:</label>
                                            <input type="password" class="form-control"
                                                   placeholder="APP_USR-xxxxxxx-xxxxxx-xxxx"
                                                   value="{{$provider->provider_key}}" name="provider_key">
                                        </div>
                                        <div class="col-lg-6">
                                            <label>Token do Client:</label>
                                            <input type="password" class="form-control"
                                                   placeholder="APP_USR-xxxxxxx-xxxxxx-xxxx"
                                                   value="{{$provider->provider_token}}" name="provider_token">
                                        </div>
                                    </div>

                                    <h3 class="kt-section__title">
                                        Credênciais [DEMO]:
                                    </h3>

                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label>Chave do Cliente:</label>
                                            <input type="password" class="form-control"
                                                   placeholder="APP_USR-xxxxxxx-xxxxxx-xxxx"
                                                   value="{{$provider->provider_key_dev}}"
                                                   name="provider_key_dev">
                                        </div>
                                        <div class="col-lg-6">
                                            <label>Token do Client:</label>
                                            <input type="password" class="form-control"
                                                   placeholder="APP_USR-xxxxxxx-xxxxxx-xxxx"
                                                   value="{{$provider->provider_token_dev}}"
                                                   name="provider_token_dev">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="reset" class="btn btn-danger float-right">REDEFINIR TUDO</button>
                    </div>
                </div>

            </form>
        </div>


    </div>
@endsection

@extends('admin')

@section('content')
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Edição do usuário</h3>
        </div>
    </div>
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">
            <div class="col-xl-4">
                <div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay">
                    <div class="kt-portlet__head kt-portlet__space-x">
                        <div class="kt-portlet__head-label" style="width: 100%;">
                            <h3 class="kt-portlet__head-title text-center" style="width: 100%;">
                                {{$user->username}}
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-widget28">
                            <div class="kt-widget28__visual"
                                 style="background: url({{$user->avatar}}) bottom center no-repeat">
                            </div>
                            <div class="kt-widget28__wrapper kt-portlet__space-x">
                                <div class="tab-content">
                                    <div id="menu11" class="tab-pane active">
                                        <div class="kt-widget28__tab-items">
                                            <div class="kt-widget12">
                                                @if(!$user->fake)
                                                    <div class="kt-widget12__content">
                                                        <div class="kt-widget12__item">
                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Depósitos</span>
                                                                <span class="kt-widget12__value">R$ {{$pay}}</span>
                                                            </div>

                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Saques</span>
                                                                <span class="kt-widget12__value">R$ {{$withdraw}}</span>
                                                            </div>
                                                        </div>
                                                        <div class="kt-widget12__item">
                                                            <div class="kt-widget12__info text-center">
                                                                <span
                                                                        class="kt-widget12__desc">Lucros obtidos por indicações</span>
                                                                <span
                                                                        class="kt-widget12__value">R$ {{$totalInviteProfit}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="kt-widget12__content">
                                                        <h6 class="block capitalize-font text-center">
                                                            Apostas em Jackpot
                                                        </h6>
                                                        <div class="kt-widget12__item">
                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Lucrou</span>
                                                                <span
                                                                    class="kt-widget12__value">R$ {{$jackpotWin}}</span>
                                                            </div>

                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Perdas</span>
                                                                <span
                                                                    class="kt-widget12__value">R$ {{$jackpotLose}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="kt-widget12__content">
                                                        <h6 class="block capitalize-font text-center">
                                                            Apostas em Wheel
                                                        </h6>
                                                        <div class="kt-widget12__item">
                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Lucrou</span>
                                                                <span class="kt-widget12__value">R$ {{$wheelWin}}</span>
                                                            </div>

                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Perdas</span>
                                                                <span
                                                                    class="kt-widget12__value">R$ {{$wheelLose}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="kt-widget12__content">
                                                        <h6 class="block capitalize-font text-center">
                                                            Apostas em Crash
                                                        </h6>
                                                        <div class="kt-widget12__item">
                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Lucrou</span>
                                                                <span class="kt-widget12__value">R$ {{$crashWin}}</span>
                                                            </div>

                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Perdas</span>
                                                                <span
                                                                    class="kt-widget12__value">R$ {{$crashLose}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="kt-widget12__content">
                                                        <h6 class="block capitalize-font text-center">
                                                            Apostas em PvP
                                                        </h6>
                                                        <div class="kt-widget12__item">
                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Lucrou</span>
                                                                <span class="kt-widget12__value">R$ {{$coinWin}}</span>
                                                            </div>

                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Perdas</span>
                                                                <span class="kt-widget12__value">R$ {{$coinLose}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="kt-widget12__content">
                                                        <h6 class="block capitalize-font text-center">
                                                            Apostas em Battle
                                                        </h6>
                                                        <div class="kt-widget12__item">
                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Lucrou</span>
                                                                <span
                                                                    class="kt-widget12__value">R$ {{$battleWin}}</span>
                                                            </div>

                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Perdas</span>
                                                                <span
                                                                    class="kt-widget12__value">R$ {{$battleLose}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="kt-widget12__content">
                                                        <h6 class="block capitalize-font text-center">
                                                            Apostas em Dice
                                                        </h6>
                                                        <div class="kt-widget12__item">
                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Lucrou</span>
                                                                <span class="kt-widget12__value">R$ {{$diceWin}}</span>
                                                            </div>

                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Perdas</span>
                                                                <span class="kt-widget12__value">R$ {{$diceLose}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="kt-widget12__content">
                                                        <h6 class="block capitalize-font text-center">
                                                            Resultado Completo
                                                        </h6>
                                                        <div class="kt-widget12__item">
                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Lucros em Tudo</span>
                                                                <span class="kt-widget12__value">R$ {{$betWin}}</span>
                                                            </div>

                                                            <div class="kt-widget12__info text-center">
                                                                <span class="kt-widget12__desc">Perdas em Tudo</span>
                                                                <span class="kt-widget12__value">R$ {{$betLose}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Informação do usuário
                            </h3>
                        </div>
                    </div>
                    <!--begin::Form-->
                    @if(!$user->fake)
                        <form class="kt-form" method="post" action="/admin/user/save">
                            <div class="kt-portlet__body">
                                <input name="id" value="{{$user->id}}" type="hidden">
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>Nome:</label>
                                        <input type="text" class="form-control" value="{{$user->username}}" disabled>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="">Endereço de IP:</label>
                                        <input type="text" class="form-control" value="{{$user->ip}}" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>Saldo:</label>
                                        <div class="kt-input-icon">
                                            <input type="text" class="form-control" name="balance"
                                                   value="{{$user->balance}}">
                                            <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i
                                                        class="la la-rub"></i></span></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Bônus:</label>
                                        <div class="kt-input-icon">
                                            <input type="text" class="form-control" name="bonus"
                                                   value="{{$user->bonus}}">
                                            <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i
                                                        class="la la-diamond"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>Privilégios:</label>
                                        <select class="form-control" name="priv">
                                            <option value="admin" @if($user->is_admin) selected @endif>Administrador
                                            </option>
                                            <option value="moder" @if($user->is_moder) selected @endif>Moderador
                                            </option>
                                            <option value="youtuber" @if($user->is_youtuber) selected @endif>YouTube`r
                                            </option>
                                            <option value="user"
                                                    @if(!$user->is_admin && !$user->is_moder && !$user->is_youtuber) selected @endif>
                                                Usuário
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">             
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label class="">Banir do site:</label>
                                        <select class="form-control" name="ban">
                                            <option value="0" @if($user->ban == 0) selected @endif>Não</option>
                                            <option value="1" @if($user->ban == 1) selected @endif>Sim</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>O motivo do banimento:</label>
                                        <div class="kt-input-icon">
                                            <input type="text" class="form-control" name="ban_reason"
                                                   value="{{$user->ban_reason}}">
                                            <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i
                                                        class="la la-exclamation-triangle"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label class="">Banimento no Chat:</label>
                                        <div class="kt-input-icon">
                                            <input type="text" class="form-control" name="banchat"
                                                   value="{{ !is_null($user->banchat) ? \Carbon\Carbon::parse($user->banchat)->format('d.m.Y - H:i:s') : '' }}">
                                            <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i
                                                        class="la la-calendar-o"></i></span></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Motivo de banimento no Chat:</label>
                                        <div class="kt-input-icon">
                                            <input type="text" class="form-control" name="banchat_reason"
                                                   value="{{$user->banchat_reason}}">
                                            <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i
                                                        class="la la-exclamation-triangle"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label class="">Valor apostado total:</label>
                                        <div class="kt-input-icon">
                                            <input type="text" class="form-control" name="requery"
                                                   value="{{ $user->requery }}">
                                            <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i
                                                        class="la la-rub"></i></span></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="">Link de referência:</label>
                                        <div class="kt-input-icon">
                                            <input type="text" class="form-control" name="ref_id"
                                                   value="https://{{ strtolower($settings->domain) }}/?ref={{$u->unique_id}}"
                                                   disabled>
                                            <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i
                                                        class="la la-link"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>Jogadores indicados por link de referência:</label>
                                        <div class="kt-input-icon">
                                            <input type="text" class="form-control" value="{{$user->link_reg}}"
                                                   disabled>
                                            <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i
                                                        class="la la-diamond"></i></span></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Dinheiro ganhos no sistema de referência:</label>
                                        <div class="kt-input-icon">
                                            <input type="text" class="form-control" value="{{$user->ref_money}}"
                                                   disabled>
                                            <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i
                                                        class="la la-rub"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>Todo o dinheiro ganho no sistema de referência:</label>
                                        <div class="kt-input-icon">
                                            <input type="text" class="form-control" value="{{$user->ref_money_all}}"
                                                   disabled>
                                            <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i
                                                        class="la la-rub"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-portlet__foot kt-portlet__foot--solid">
                                    <div class="kt-form__actions">
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-brand">Salvar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </form>
                    @else
                        <form class="kt-form" method="post" action="/admin/user/save">
                            <div class="kt-portlet__body">
                                <input name="id" value="{{$user->id}}" type="hidden">
                                <div class="form-group row">
                                    <input type="hidden" class="form-control" name="balance" value="{{$user->balance}}">
                                    <input type="hidden" class="form-control" name="bonus" value="{{$user->bonus}}">
                                    <input type="hidden" class="form-control" name="ban" value="{{$user->ban}}">
                                    <div class="col-lg-6">
                                        <label>Фамилия Имя:</label>
                                        <input type="text" class="form-control" value="{{$user->username}}" disabled>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Время ставки</label>
                                        <select class="form-control" name="time">
                                            <option value="1" {{ $user->time == 1 ? 'selected' : '' }}>Утром (с 6ч до
                                                12ч)
                                            </option>
                                            <option value="2" {{ $user->time == 2 ? 'selected' : '' }}>Днем (с 12ч до
                                                18ч)
                                            </option>
                                            <option value="3" {{ $user->time == 3 ? 'selected' : '' }}>Вечером (с 18ч до
                                                00ч)
                                            </option>
                                            <option value="4" {{ $user->time == 4 ? 'selected' : '' }}>Ночью (с 00ч до
                                                6ч)
                                            </option>
                                            <option value="0" {{ $user->time == 0 ? 'selected' : '' }}>Все время
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label>Privilégios:</label>
                                        <select class="form-control" name="priv">
                                            <option value="admin" @if($user->is_admin) selected @endif>Administrador
                                            </option>
                                            <option value="moder" @if($user->is_moder) selected @endif>Модератор
                                            </option>
                                            <option value="youtuber" @if($user->is_youtuber) selected @endif>YouTube`r
                                            </option>
                                            <option value="user"
                                                    @if(!$user->is_admin && !$user->is_moder && !$user->is_youtuber) selected @endif>
                                                Usuario
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Página VK:</label>
                                        <div class="kt-input-icon">
                                            <input type="text" class="form-control"
                                                   value="https://vk.com/id{{$user->user_id}}" disabled>
                                            <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i
                                                        class="la la-vk"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet__foot kt-portlet__foot--solid">
                                <div class="kt-form__actions">
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-brand">Salvar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                    <!--end::Form-->
                </div>
            </div>
        </div>
    </div>
@endsection

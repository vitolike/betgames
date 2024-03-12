@extends('layout')

@section('content')
    <link rel="stylesheet" href="/css/profileHistory.css">
    <script type="text/javascript" src="/js/profileHistory.js"></script>
    <div class="section">
        <div class="wallet_container">
            <div class="wallet_component">
                <div class="history_nav">
                    <button type="button" class="btn isActive" data-tab="my_profile"><span>Alterar minha senha</span></button>
                </div>

                <div class="history_wrapper change_pass" >
                    <div class="withPager">
                        <div class="form-data history_empty">

                            <form class="kt-form" method="post" action="/forgot-password" id="save">

                                <div class="form-row">
                                    <label for="exampleFormControlInput1" class="form-label">Digite aqui sua nova senha</label>
                                    <input type="password" name="password" placeholder="Digite aqui sua nova senha"
                                           class="input-field">

                                    <input type="text" name="id" hidden value="{{$user->id}}">
                                </div>

                                <button type="submit" class="btn" style><span>Salvar Nova Senha</span></button>

                            </form>

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection

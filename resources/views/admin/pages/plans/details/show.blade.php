@extends('adminlte::page')

@section('title', '- Detalhe de Plano')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-file"></i> Detalhe do Plano {{ $plan->name }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('plans.index') }}">Planos</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('plans.show', ['plan' => $plan->id]) }}">Plano
                                {{ $plan->name }}</a></li>
                        <li class="breadcrumb-item active"><a
                                href="{{ route('details.index', ['id' => $plan->id]) }}">Detalhes do Plano
                                {{ $plan->name }}</a></li>
                        <li class="breadcrumb-item active">Detalhe do Plano</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    @include('components.alert')

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Dados Cadastrais do Plano</h3>
                        </div>

                        <div class="card-body">

                            <div class="d-flex flex-wrap justify-content-start">
                                <div class="col-12 col-md-3 form-group px-0 pr-md-2">
                                    <label for="name">Nome</label>
                                    <input type="text" class="form-control bg-white" id="name" name="name"
                                        value="{{ $detail->name }}" disabled>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

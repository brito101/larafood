@extends('adminlte::page')
@section('plugins.select2', true)
@section('plugins.BootstrapSelect', true)


@section('title', '- Edição de Plano')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-file"></i> Editar Plano</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.plans.index') }}">Planos</a></li>
                        <li class="breadcrumb-item active">Editar Plano</li>
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

                        <form method="POST" action="{{ route('admin.plans.update', ['plan' => $plan->id]) }}">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="id" value="{{ $plan->id }}">
                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-start">
                                    <div class="col-12 col-md-3 form-group px-0 pr-md-2">
                                        <label for="name">Nome</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Nome do Plano" name="name"
                                            value="{{ old('name') ?? $plan->name }}" required>
                                    </div>
                                    <div class="col-12 col-md-3 form-group px-0 px-md-2">
                                        <label for="price">Preço</label>
                                        <input type="text" class="form-control" id="price" placeholder="Preço"
                                            name="price" value="{{ old('price') ?? $plan->price_br }}" required>
                                    </div>
                                    <div class="col-12 col-md-6 form-group px-0 pl-md-2">
                                        <label for="description">Descrição</label>
                                        <input type="text" class="form-control" id="description"
                                            placeholder="Texto descritivo" name="description"
                                            value="{{ old('description') ?? $plan->description }}" required>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between">

                                    @php
                                        $config = [
                                            'title' => 'Selecione múltiplas opções',
                                            'showTick' => true,
                                            'actionsBox' => true,
                                        ];
                                    @endphp
                                    <div class="col-12 col-md-6 form-group px-0 pr-md-2 mb-0">
                                        <x-adminlte-select-bs id="roles" name="roles[]" label="Perfis Participantes"
                                            igroup-size="md" :config="$config" multiple class="border-grey border">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    {{ in_array($role->id, $plan->profiles->pluck('role_id')->toArray()) ? 'selected' : '' }}>
                                                    {{ $role->name }}</option>
                                            @endforeach
                                        </x-adminlte-select-bs>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

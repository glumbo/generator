@extends ('backend.layouts.app')

@section ('title', _tr('generator::labels.modules.management') . ' | ' . _tr('generator::labels.modules.edit'))

@section('page-header')
    <h1>
        {{ _tr('generator::labels.modules.management') }}
        <small>{{ _tr('generator::labels.modules.edit') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($module, ['route' => ['admin.modules.update', $module], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'id' => 'edit-module', 'files' => true]) }}

        <div class="card card-info">
            <div class="card-header with-border">
                <h3 class="box-title">{{ _tr('generator::labels.modules.edit') }}</h3>

                <div class="card-tools float-end">
                    @include('generator::partials.modules-header-buttons')
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            {{-- Including Form blade file --}}
            <div class="card-body">
                <div class="form-group">
                    @include("backend.modules.form")
                    <div class="edit-form-btn">
                    {{ link_to_route('admin.modules.index', _tr('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-md']) }}
                    {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-primary btn-md']) }}
                    <div class="clearfix"></div>
                </div>
            </div>
        </div><!--box-->
    </div>
    {{ Form::close() }}
@endsection
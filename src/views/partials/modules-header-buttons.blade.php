<!--Action Button-->
    @if(request()->is('admin/modules'))
      <div class="btn-group">
        <button type="button" class="btn btn-warning btn-flat dropdown-toggle" data-bs-toggle="dropdown">{{_tr('labels.general.export')}}
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
          <li id="copyButton"><a href="#" class="dropdown-item"><i class="bi bi-copy"></i> {{ _tr('labels.general.copy') }}</a></li>
          <li id="csvButton"><a href="#" class="dropdown-item"><i class="bi bi-file-text"></i> {{ _tr('labels.general.csv') }}</a></li>
          <li id="excelButton"><a href="#" class="dropdown-item"><i class="bi bi-file-excel"></i> {{ _tr('labels.general.excel') }}</a></li>
          <li id="pdfButton"><a href="#" class="dropdown-item"><i class="bi bi-file-pdf"></i> {{ _tr('labels.general.pdf') }}</a></li>
          <li id="printButton"><a href="#" class="dropdown-item"><i class="bi bi-printer"></i> {{ _tr('labels.general.print') }}</a></li>
        </ul>
      </div>
    @endif
<!--Action Button-->
<div class="btn-group">
  <button type="button" class="btn btn-primary btn-flat dropdown-toggle" data-bs-toggle="dropdown">{{_tr('labels.general.action')}}
    <span class="caret"></span>

  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="{{route('admin.modules.index')}}"><i class="fal fa-list-ul"></i> {{trans('menus.backend.modules.all')}}</a></li>
    <li><a href="{{route('admin.modules.create')}}"><i class="fal fa-plus"></i> {{trans('menus.backend.modules.create')}}</a></li>
  </ul>
</div>

<div class="clearfix"></div>
<!--Action Button-->
    @if(Active::checkUriPattern('admin/modules'))
        <div class="btn-group">
          <button type="button" class="btn btn-warning btn-flat dropdown-toggle" data-toggle="dropdown">Export
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
          </button>
          <ul class="dropdown-menu" role="menu">
            <li id="copyButton"><a href="#"><i class="fal fa-clone"></i> Copy</a></li>
            <li id="csvButton"><a href="#"><i class="fal fa-file-text"></i> CSV</a></li>
            <li id="excelButton"><a href="#"><i class="fal fa-file-excel"></i> Excel</a></li>
            <li id="pdfButton"><a href="#"><i class="fal fa-file-pdf"></i> PDF</a></li>
            <li id="printButton"><a href="#"><i class="fal fa-print"></i> Print</a></li>
          </ul>
        </div>
    @endif
<!--Action Button-->
<div class="btn-group">
  <button type="button" class="btn btn-primary btn-flat dropdown-toggle" data-toggle="dropdown">Action
    <span class="caret"></span>
    <span class="sr-only">Toggle Dropdown</span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="{{route('admin.modules.index')}}"><i class="fal fa-list-ul"></i> {{trans('menus.backend.modules.all')}}</a></li>
    <li><a href="{{route('admin.modules.create')}}"><i class="fal fa-plus"></i> {{trans('menus.backend.modules.create')}}</a></li>
  </ul>
</div>

<div class="clearfix"></div>
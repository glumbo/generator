<div class="box-body">
    <div class="form-group mb-2">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="alert alert-warning">
                Note : You need to have 0777 permission to all folders of the project.
            </div>
        </div>
    </div>
    <!-- Module Name -->
    <div class="form-group mt-2">
        {{ Form::label('name', _tr('generator::labels.modules.form.name'), ['class' => 'col-lg-2 control-label form-label mt-2 required']) }}

        <div class="col-lg-10">
            {{ Form::text('name', null, ['class' => 'form-control box-size', 'placeholder' => 'e.g., Blog', 'required' => 'required']) }}
        </div><!--col-lg-10-->
    </div>

    <!-- Directory -->
    <div class="form-group mt-2">
        {{ Form::label('directory_name', _tr('generator::labels.modules.form.directory_name'), ['class' => 'col-lg-2 control-label form-label mt-2 required']) }}

        <div class="col-lg-10">
            {{ Form::text('directory_name', null, ['class' => 'form-control box-size', 'placeholder' => 'e.g., Blog', 'required' => true]) }}
        </div><!--col-lg-10-->
    </div>
    <!-- End Directory -->

    <!-- Model Name -->
    <div class="form-group mt-2">
        {{ Form::label('model_name', _tr('generator::labels.modules.form.model_name'), ['class' => 'col-lg-2 control-label form-label mt-2 required']) }}

        <div class="col-lg-10">
            {{ Form::text('model_name', null, ['class' => 'form-control box-size only-text', 'placeholder' => 'e.g., Blog', 'required' => true]) }}
            <div class="model-messages"></div>
        </div>
    </div>
    <!-- End Model Name -->

    <!-- Table Name -->
    <div class="form-group mt-2">
        {{ Form::label('table_name', _tr('generator::labels.modules.form.table_name'), ['class' => 'col-lg-2 control-label form-label mt-2']) }}

        <div class="col-lg-10">
            {{ Form::text('table_name', null, ['class' => 'form-control box-size', 'placeholder' => 'e.g., Blog']) }}
            <div class="table-messages"></div>
        </div><!--col-lg-10-->
    </div>
    <!-- End Table Name -->

    <!-- Crud Operations Create/Edit/Delete to be added to the field (Read operation is given by default)-->
    <div class="form-group mt-2 row">
        {{ Form::label('operations', 'CRUD Operations', ['class' => 'col-lg-2 control-label form-label mt-2']) }}
        <div class="col-lg-8">
            <div class="form-check form-check-inline">
                <!-- For Create Operation of CRUD -->
                {{ Form::checkbox('model_create', '1', false, ['class' => 'form-check-input']) }}
                <label class="form-check-label">{{ _tr('create') }}</label>
                <div class="control__indicator"></div>
            </div>
            <div class="form-check form-check-inline">
                <!-- For Edit Operation of CRUD -->
                {{ Form::checkbox('model_edit', '1', false, ['class' => 'form-check-input']) }}
                <label class="form-check-label">{{ _tr('edit') }}</label>
                <div class="control__indicator"></div>
            </div>
            <div class="form-check form-check-inline">
                <!-- For Delete Operation of CRUD -->
                {{ Form::checkbox('model_delete', '1', false, ['class' => 'form-check-input']) }}
                <label class="form-check-label">{{ _tr('delete') }}</label>
                <div class="control__indicator"></div>
            </div>
            <div class="form-check form-check-inline">
                <!-- For Upload Operation of CRUD -->
                {{ Form::checkbox('model_upload', '1', false, ['class' => 'form-check-input']) }}
                <label class="form-check-label">{{ _tr('upload') }}</label>
                <div class="control__indicator"></div>
            </div>
        </div>
    </div>
    <div id="columns">

    </div>
    <div id="column" class="form-group d-none mb-2">
        <div class="col-lg-2 control-label">
            {{ Form::label('operations', _tr('generator::labels.modules.form.column'), ['class' => 'control-label form-label mt-2']) }}
        </div>
        <div class="row">
            <div class="col-lg-3 mb-1">
                {{ Form::text('columns[x][name]', null, ['class' => 'form-control', 'placeholder' => 'e.g., name']) }}
            </div>
            <div class="col-lg-2 mb-1">
                {{ Form::select('columns[x][type]', ["Integer", "String", "Text", "Decimal", "Timestamp", "File", "Select"], null, ['class' => 'form-control status type', 'placeholder' => _tr('generator::labels.modules.form.type')]) }}
            </div>
            <div class="col-lg-2 mb-1">
                <div class="form-check form-check-inline">
                    <!-- For Delete Operation of CRUD -->
                    {{ Form::checkbox('columns[x][nullable]', '1', false, ['class' => 'form-check-input', 'id' => 'column_nullable_x']) }}
                    <label class="form-check-label" for="column_nullable_x">{{ _tr('generator::labels.modules.form.nullable') }}</label>
                    <div class="control__indicator"></div>
                </div>
            </div>
            <div class="col-lg-2 mb-1">
                {{ Form::text('columns[x][default]', null, ['class' => 'form-control default', 'placeholder' => _tr('generator::labels.modules.form.default')]) }}
            </div>
            <div class="col-lg-2">
                <a href="javascript:;" class="btn btn-danger btn-sm remove_column"><i class="fal fa-times"></i></a>
            </div>
        </div>
    </div>
    <div class="form-group mb-2 mt-2">
        <div class="col-lg-2"></div>
        <div class="col-lg-10">
            <a href="javascript:;" class="btn btn-info btn-md add_column"><i class="fal fa-plus"></i> {{_tr('generator::labels.modules.form.add_column')}}</a>
        </div>
    </div>
    <!-- End Crud Operations -->
    <div class="box-header text-center mb-2">
        <hr width=100%/>
        <h3 class="box-title"> {{_tr('generator::labels.modules.form.optional')}} </h3>
        <hr width=100%/>
    </div><!-- /.box-header -->
    <!-- Events --> 
    <div class="events-div mt-2">
        <div class="form-group event clearfix">
            {{ Form::label('event[]', _tr('generator::labels.modules.form.event'), ['class' => 'col-lg-2 control-label form-label mt-2']) }}

            <div class="col-lg-6 mb-2">
                {{ Form::text('event[]', null, ['class' => 'form-control box-size', 'placeholder' => _tr('generator::labels.modules.form.event'), 'style' => 'width:100%']) }}
            </div><!--col-lg-10-->
            <a href="#" class="btn btn-danger btn-md remove-field d-none"> {{_tr('remove_event')}}</a>
            <a href="#" class="btn btn-info btn-md add-field">{{ _tr('add_event') }}</a>
        </div><!--form control-->
    </div>

    <div class="el-messages">
    </div>
    <!-- End Events -->

    <!-- To Show the generated File -->
    <div class="box-body">
        <!--All Files -->
        <div class="form-group mt-2">
            <label class="col-lg-2 control-label form-label mt-2">{{ _tr('files_to_be_generated') }}</label>
            <div class="col-lg-10">
                <textarea class="form-control box-size files" contenteditable="true" rows=15 readonly="" disabled></textarea>
            </div>
        </div>
        <!-- All Files -->
    </div>
    <!-- End The File Generated Textbox -->

    <!-- Override CheckBox -->
    <div class="form-group">
        <div class="col-lg-2"></div>
        <div class="col-lg-10">
            <p><strong>Note : </strong> The Files would be overwritten, if already exists. Please look at files (and their respective paths) carefully before creating.</p>
        </div><!--form control-->
    </div>
    <!-- end Override Checkbox -->
</div>
@section("after-styles")
    <style>
        .form-check-inline{
            margin-top: 0!important;
        }
        .form-check .form-check-input{
            padding: 15px!important;
        }
    </style>
@endsection
@section("after-scripts")
    <script src="{!! asset('public/js/backend/pluralize.js') !!}"></script>
    <script type="text/javascript">
        //When the DOM is ready to be manipulated
        $(document).ready(function(){
            model_ns = {!! json_encode($model_namespace) !!};
            controller_ns = {!! json_encode($controller_namespace) !!};
            event_ns = {!! json_encode($event_namespace) !!};
            request_ns = {!! json_encode($request_namespace) !!};
            repo_ns = {!! json_encode($repo_namespace) !!};
            route_path = {!! json_encode($route_path) !!};
            view_path = {!! json_encode($view_path) !!};

            //If any errors occured
            handleAllCheckboxes();
            //events and listeners checkbox change event
            $("input[name=el]").on('change', function(e){
                handleCheckBox($(this), $(".el"));
            });
            //Add field in event panel
            $(document).on('click', ".add-field", function(e){
                e.preventDefault();
                clone = $(".event").first().clone();
                clone.find(".remove-field").removeClass('d-none');
                clone.appendTo(".events-div");
            });
            //remove field in event panel
            $(document).on('click', ".remove-field", function(e){
                e.preventDefault();
                $(this).parent('div').remove();
            });
            //model name on blur event
            $(document).on('blur', "input[name=model_name]", function(e){
                getFilesGenerated();
                table = pluralize($(this).val());
                $("input[name=table_name]").val(table.toLowerCase());
            });
            //Directory name blur event
            $(document).on('blur', "input[name=directory_name]", function(e){
                getFilesGenerated();
            });
            //Model Create Checkbox change event
            $(document).on('change', "input[name=model_create]", function(e){
                getFilesGenerated();
            });
            //Model Edit Checkbox change event
            $(document).on('change', "input[name=model_edit]", function(e){
                getFilesGenerated();
            });
            //Model Delete Checkbox change event
            $(document).on('change', "input[name=model_delete]", function(e){
                getFilesGenerated();
            });
            //table name on blur event
            $(document).on('blur', "input[name=table_name]", function(e){
                checkTableExists($(this));
            });
            //Events Change Event
            $(document).on('change', "input[name='event[]']", function(e){
                getFilesGenerated();
            });
        });

        function checkModelExists(model) {
            if(model.val()) {
                path = getPath( model_ns, $("input[name=model_namespace]").val(), model.val());
                checkPath(path, 'model');
            } else {
                throwMessages('error', 'Please provide some input.', "model");
            }
        }

        function checkTableExists(table) {
            if(table.val()){
                $.post( "{{ route('admin.modules.check.table') }}", { 'table' : table.val()} )
                .done( function( data ) {
                    throwMessages(data.type, data.message, "table");
                });
            } else {
                 throwMessages('error', "Please provide some input.", "table");
            }
        }

        function checkEventExists(event) {
            if(event.val() && $("input[name=event_namespace]").val()) {
                path = getPath( event_ns, $("input[name=event_namespace]").val(), event.val());
                checkPath(path, 'el');
            } else {
                throwMessages('error', 'Please provide some input.', "el");
            }
        }
        function getPath(ns, namespace, model) {
            if(dir = $("input[name=directory_name]").val()) {
                return ns + namespace + "\\" + dir + "\\" + model;
            } else {
                return ns + namespace + "\\" +  model;
            }
        }

        function checkPath(path, element) {
            $.post( "{{ route('admin.modules.check.namespace') }}", { 'path' : path} )
            .done( function( data ) {
                throwMessages(data.type, data.message, element);
            });
        }

        function throwMessages(type, message, element) {
            appendMessage = '';
            switch(type) {
                case 'warning' :
                    appendMessage = "<span class='"+ element +"-warning'><i class='fal fa-exclamation-triangle' aria-hidden='true'></i>&nbsp; "+ message +"</span>";
                    break;
                case 'error' :
                    appendMessage = "<span class='"+ element +"-error'><i class='fal fa-exclamation-circle' aria-hidden='true'></i>&nbsp; "+ message +"</span>";
                    break;
                case 'success' :
                    appendMessage = "<span class='"+ element +"-success'><i class='fal fa-check' aria-hidden='true'></i>&nbsp; "+ message +"</span>";
            }

            $("."+element+"-messages").html(appendMessage);

        }
        function getFilesGenerated() {
            model = $("input[name=model_name]").val();
            if(model) {
                separator = "" ;
                if(dir = $("input[name=directory_name]").val()) {
                    model_nspace = model_ns + dir;
                    controller_nspace = controller_ns + dir;
                    request_nspace = request_ns + dir;
                    repo_nspace = repo_ns + dir;
                    event_nspace = event_ns + dir;
                    views_path = view_path + pluralize(dir.toLowerCase());
                    separator = "\\";
                }
                else {
                    model_nspace = model_ns;
                    controller_nspace = controller_ns;
                    request_nspace = request_ns;
                    repo_nspace = repo_ns;
                    event_nspace = event_ns;
                    views_path = view_path;
                }
                list_nspace = event_nspace.replace("Events", "Listeners");
                directory_separator = "\\";
                files = [];
                model_plural = pluralize(model);
                files.push(model_nspace + separator + model + ".php\n");
                files.push(model_nspace + separator + "Traits" + directory_separator + model + "Attribute.php\n");
                files.push(model_nspace + separator + "Traits" + directory_separator + model + "Relationship.php\n");
                files.push("\n" + controller_nspace + separator +model_plural + "Controller.php\n");
                files.push(controller_nspace + separator +model_plural + "TableController.php\n");
                create = $("input[name=model_create]").prop('checked');
                edit = $("input[name=model_edit]").prop('checked');
                del = $("input[name=model_delete]").prop('checked');
                files.push("\n");
                if(create) {
                    files.push(request_nspace + separator + "Create" + model + "Request.php\n");
                    files.push(request_nspace + separator + "Store" + model + "Request.php\n");
                }
                if(edit) {
                    files.push(request_nspace + separator + "Edit" + model + "Request.php\n");
                    files.push(request_nspace + separator + "Update" + model + "Request.php\n");
                }
                if(del) {
                    files.push(request_nspace + separator + "Delete" + model + "Request.php\n");
                }
                files.push("\n" + views_path + separator + "index.blade.php\n");
                if(create) {
                    files.push(views_path + separator + "create.blade.php\n");
                }
                if(edit) {
                    files.push(views_path + separator + "edit.blade.php\n");
                }
                if(create || edit) {
                    files.push(views_path + separator + "form.blade.php\n");
                }
                files.push("\n");
                files.push(route_path + model + ".php\n");
                files.push("\n");
                files.push(repo_nspace + separator + model + "Repository.php\n");
                files.push("\n");
                $(document).find('input[name="event[]"]').each(function(){
                    if(e = $(this).val()) {
                        files.push(event_nspace + separator + e + ".php\n");
                        files.push(list_nspace + separator + e + "Listener.php\n");
                    }
                });
                files = files.toString().replace (/,/g, "");
                $(".files").val(files);
            }
        }
        //If any errors occured,
        //the panels should automatically be opened
        //which were opened before
        function handleAllCheckboxes() {
            handleCheckBox($("input[name=model]"), $(".model"));
            handleCheckBox($("input[name=controller]"), $(".controller"));
            handleCheckBox($("input[name=table_controller]"), $(".table_controller"));
            handleCheckBox($("input[name=table]"), $(".table"));
            handleCheckBox($("input[name=route]"), $(".route"));
            handleCheckBox($("input[name=views]"), $(".views"));
            handleCheckBox($("input[name=el]"), $(".el"));
            handleCheckBox($("input[name=repository]"), $(".repository"));
            throwMessages('warning', 'The table name can only contain characters and digits and underscores[_].', 'table');
            throwMessages('warning', 'The files with the same name would be overwritten.', 'views');
        }

        //Handle the checkbox event for that element
        function handleCheckBox(checkbox, element){
            checkboxValue = checkbox.prop('checked');
            if($("."+checkbox.attr('name')+"-messages").children().length == 0) {
                $("."+checkbox.attr('name')+"-messages").empty();
            }
            if(checkboxValue) {
                element.removeClass('d-none', 3000);
            }
            else {
                element.addClass('d-none', 3000);
            }

            //calling required field handler functions
            switch (checkbox.attr('name')) {
                case 'model' : handleModelRequiredFields(checkboxValue);
                    break;
                case 'controller' : handleControllerRequiredFields(checkboxValue);
                    break;
                case 'table' : handleTableRequiredFields(checkboxValue);
                    break;
                case 'route' : handleRouteRequiredFields(checkboxValue);
                    break;
                case 'repository' : handleRepoRequiredFields(checkboxValue);
                    break;
                case 'el' : handleEventRequiredFields(checkboxValue);
                    break;
            }
        }

        //Events Required fields if that panel is open
        function handleEventRequiredFields(check) {
            $("input[name=event_namespace]").attr('required', check);
            $("input[name='event[]']").attr('required', check);
        }
        //For changing namespace
        // function changeNamespace(val, ns, element) {
        //     if(!val) {
        //         val = ns.replace('/\\\\/g', '');
        //     } else {
        //         val = ns + "\\" + val + "\\";
        //     }
        //     element.text(val);
        // }

        //For only characters
        $( document ).on('keyup', ".only-text", function(e) {
            var val = $(this).val();
            if (val.match(/[^a-zA-Z]/g)) {
               $(this).val(val.replace(/[^a-zA-Z]/g, ''));
            }
        });
        $('body').on("click", ".add_column", function(){
            var count = $("#columns").find(".column").length;
            var column = $("#column").html();

            column = column.replaceAll('[x]', '['+count+']');
            column = column.replaceAll('_x', '_'+count);

            column = '<div class="form-group column" data-count="'+count+'" >' + column + '</div>';
            $("#columns").append(column);
        });
        $('body').on("click","a.remove_column", function(){
            var column = $(this).closest(".column");
            let e = column.remove();
        });

        $('body').on("change", "select.type", function () {
            var column = $(this).closest(".column");

            var count = column.data('count');
            if($(this).val() == 6){
                column.find(".default").attr("name","columns["+count+"][relation]").attr("placeholder","Relation");
            }else{
                column.find(".default").attr("name","columns["+count+"][default]").attr("placeholder","Default");
            }
            
        })
    </script>
@endsection

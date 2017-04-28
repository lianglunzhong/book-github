@extends('admin.master2')

@section('content')

<article class="cl pd-20">
    <form action="" method="post" class="form form-horizontal" id="form-category-edit">
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>分类名称：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{ $category->name }}" placeholder="" name="name">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>分类NO.：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{ $category->category_no }}" placeholder="" name="category_no">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3">父级分类：</label>
            <div class="formControls col-xs-8 col-sm-9"> <span class="select-box">
                <select class="select" size="1" name="parent_id">
                    <option value="">请选择父级分类</option>
                    @foreach ($categorys as $temp)
                        @if ($temp->id == $category->parent_id)
                            <option value="{{ $temp->id }}" selected="selected">{{ $temp->name }}</option>
                        @else
                            <option value="{{ $temp->id }}">{{ $temp->name }}</option>
                        @endif
                    @endforeach
                </select>
                </span> </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3">预览图：</label>
            <div class="formControls col-xs-8 col-sm-9">
                @if ($category->preview)
                    <img id="preview_id" src="{{ $category->preview }}" style="width: 120px;cursor: pointer;" onclick="$('#preview_input_id').click()">
                @else
                    <img id="preview_id" src="/admin/static/h-ui.admin/images/load.jpg" style="width: 120px;cursor: pointer;" onclick="$('#preview_input_id').click()">
                @endif
                <input type="file" name="file" id="preview_input_id" style="display: none;" onchange="return _UploadPreview('preview_input_id','images', 'preview_id');">
            </div>
        </div>
        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
            </div>
        </div>
    </form>
</article>

@endsection

@section('my-js')

<script type="text/javascript">
    $("#form-category-edit").validate({
        rules:{
            name:{
                required:true,
                minlength:2,
                maxlength:16
            },
            category_no:{
                required:true,
                digits:true,
                minlength:5,
                maxlength:10,
            },            
        },
        onkeyup:false,
        focusCleanup:true,
        success:"valid",
        submitHandler:function(form){
            $(form).ajaxSubmit({
                type: 'post',
                url: '/admin/service/category/edit',
                dataType: 'json',
                data: {
                    id: '{{ $category->id }}',
                    name: $('input[name=name]').val(),
                    category_no: $('input[name=category_no]').val(),
                    parent_id:$('select[name=parent_id] option:selected').val(),
                    preview: ($('#preview_id').attr('src') == '/admin/static/h-ui.admin/images/load.jpg' ? '' : $('#preview_id').attr('src')),
                    _token:"{{ csrf_token() }}"
                },
                // beforeSend: function(xhr) {
                //     layer.load(0, {shade:false});
                // },
                success: function(data) {
                    if(data == null) {
                        layer.msg('服务端错误', {icon:2, time:2000});
                        return ;
                    }
                    if(data.status != 0) {
                        layer.msg(data.message, {icon:2, time:2000});
                        return ;
                    }

                    layer.msg(data.message, {icon:1, time:2000});
                    parent.location.reload();
                },
                error: function(xhr, status, error) {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                    layer.msg('ajax.error', {icon:2, time:2000});
                },
            });
            // var index = parent.layer.getFrameIndex(window.name);
            // parent.$('.btn-refresh').click();
            // parent.layer.close(index);

        },
    });
</script>

<script type="text/javascript">
    function _UploadPreview(fileElmId, type, id)
    {
        $("#"+id).attr("src", "/admin/static/h-ui.admin/images/loading.gif");
        // var file = document.getElementById("preview_input_id");
        // var fileobj = file.files;
        // console.log(fileobj);

        $.ajaxFileUpload({
            url: '/admin/service/upload/' + type,
            fileElementId: fileElmId,
            dataType: 'text',
            success: function (data)
            {
                var result = JSON.parse(data);
                console.log(result);
                if(result == null) {
                    layer.msg(result.message, {icon:2, time:2000});
                    $("#"+id).attr("src", "/admin/static/h-ui.admin/images/load.jpg");
                    return;
                }

                if(result.status != 0) {
                    layer.msg(result.message, {icon:2, time:2000});
                    $("#"+id).attr("src", "/admin/static/h-ui.admin/images/load.jpg");
                    return;
                }

                layer.msg(result.message, {icon:1, time:2000});
                $("#"+id).attr("src", result.uri);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
        return false;
    }
</script>

@endsection
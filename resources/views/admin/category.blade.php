@extends('admin.master')

@section('title', '分类列表')

@section('content')

<section class="Hui-article-box">
    <nav class="breadcrumb"><a href="/admin/index"><i class="Hui-iconfont">&#xe67f;</i> 首页 </a><span class="c-gray en">&gt;</span> 产品管理 <span class="c-gray en">&gt;</span> 分类列表<a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="Hui-article">
        <article class="cl pd-20">
            <div class="text-c">
                <input type="text" class="input-text" style="width:250px" placeholder="输入分类名称" id="" name="">
                <button type="submit" class="btn btn-success radius" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜分类</button>
            </div>
            <div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:;" onclick="datadel()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> <a href="javascript:;" onclick="_CategoryAdd('添加分类','/admin/category/add','','510')" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加分类</a></span> <span class="r">共有数据：<strong>{{ count($categorys) }}</strong> 条</span> </div>
            <div class="mt-20">
                <table class="table table-border table-bordered table-hover table-bg table-sort">
                    <thead>
                        <tr class="text-c">
                            <th width="25"><input type="checkbox" name="" value=""></th>
                            <th width="80">ID</th>
                            <th width="100">分类名称</th>
                            <th width="80">分类名称</th>
                            <th width="100">父级分类</th>
                            <th width="120">创建时间</th>
                            <th width="120">更新时间</th>
                            <th width="100">预览图</th>
                            <th width="100">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categorys as $category)
                            <tr class="text-c">
                                <td><input type="checkbox" value="{{ $category->id }}" name=""></td>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->category_no }}</td>
                                <td>
                                    @if ($category->parent_id != null)
                                        {{ $category->parent->name }}
                                    @endif
                                </td>
                                <td>{{ $category->created_at }}</td>
                                <td>{{ $category->updated_at }}</td>
                                <td class="product-thumb">
                                    @if ($category->preview)
                                        <a href="{{ $category->preview }}" data-lightbox="gallery{{ $category->id }}">
                                            <img style="width: 80px;" src="{{ $category->preview }}">
                                        </a>
                                    @endif
                                </td>
                                <td class="td-manage"> 
                                    <a title="编辑" href="javascript:;" onclick="_CategoryEdit('编辑分类','/admin/category/edit/{{ $category->id }}','4','','510')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
                                    <a title="删除" href="javascript:;" onclick="_CategoryDelete(this, '{{ $category->name }}', '{{ $category->id }}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </article>
    </div>
</section>


@endsection



@section('my-js')

<script type="text/javascript">
    function _CategoryAdd(title,url,w,h) 
    {
        layer_show(title,url,w,h);
    }

    function _CategoryDelete(obj, name, category_id)
    {
        layer.confirm('确认要删除 '+name+' 分类吗？',function(index){
            $.ajax({
                type: 'post',
                url: '/admin/service/category/delete',
                dataType: 'json',
                data: {
                    category_id: category_id,
                    _token:"{{ csrf_token() }}"
                },
                // beforeSend: function(xhr) {
                //     layer.load(0, {shade:false});
                // },
                success: function(data) {
                    console.log(data);
                    if(data == null) {
                        layer.msg('服务端错误', {icon:2, time:2000});
                        return ;
                    }
                    if(data.status != 0) {
                        layer.msg(data.message, {icon:2, time:2000});
                        return ;
                    }

                    $(obj).parents("tr").remove();
                    layer.msg('已删除!',{icon:1,time:1000});
                },
                error: function(xhr, status, error) {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                    layer.msg('ajax.error', {icon:2, time:2000});
                },
            });
        });
    }

    function _CategoryEdit(title,url,id,w,h)
    {
        layer_show(title,url,w,h);
    }

    // $(function(){
    //     $.Huihover(".portfolio-area li");
    // });
</script>


@endsection
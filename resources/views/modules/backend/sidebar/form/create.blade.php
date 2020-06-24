<style>
    .tab-pan-title {
        background-color: #605ca8 !important;
        font-weight: 600;
        padding: 5px;
        margin-top: 10px;
        color: #fff;
    }

    .form-group, .tab-pan-title {
        margin-bottom: 10px;
    }

    .form-group, .tab-pan-title {
        margin-bottom: 10px;
    }

    .nav-tabs-custom .tab-content {
        background: #fff;
        padding: 10px;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
    }

    .row {
        margin-right: -15px;
        margin-left: -15px;
    }
</style>

<div class="nav-tabs-custom">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs primary">
        <li class="active">
            @if($model->id == config("covid19.DEFAULT_SIDEBAR_ID"))
                <a href="#details" data-toggle="tab">New menu</a>
            @else
                <a href="#details" data-toggle="tab">New menu</a>
            @endif
        </li>
        <div class="box-tools pull-right">
            <button type="button" id="btnSubmitCreateForm" class="btn btn-primary btn-sm">
                <i class="fa fa-plus-circle"></i> Submit new menu
            </button>
        </div>
    </ul>
    <form accept-charset="utf-8" class="form-vertical" id="form-menu" method="POST"
          action="{{route("backend::sidebars.store")}}">
        @csrf
        <input type="hidden" name="parent_id" value="{{$model->id}}">
        <div class="tab-content">
            <div class="tab-pane active disabled" id="details">
                <div class="tab-pan-title"> Create a new menu under [{{$model->menu_title}}]</div>
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="form-group required">
                            <label for="menu_title"
                                   class="control-label">Name<sup>*</sup></label>
                            <input class="form-control"
                                   required=""
                                   placeholder="Enter Name"
                                   id="menu_title"
                                   type="text"
                                   name="menu_title"
                                   value="">
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="form-group required">
                            <label for="key"
                                   class="control-label">Key<sup>*</sup></label>
                            <input class="form-control"
                                   required=""
                                   placeholder="Enter Key (Must unique)"
                                   id="slug"
                                   type="text"
                                   name="slug">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <label for="icon" class="control-label">Icon</label>
                            <input
                                    class="form-control"
                                    placeholder="fa fa-user"
                                    id="icon"
                                    type="text"
                                    name="icon">
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <label for="order" class="control-label">Order</label>
                            <input
                                    class="form-control"
                                    placeholder="Enter Order, ex : {{$menu_order}}"
                                    id="menu_order"
                                    type="number"
                                    name="menu_order">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="url" class="control-label">Url</label>
                            <input
                                    class="form-control"
                                    placeholder="Enter your admin url"
                                    id="url"
                                    type="text"
                                    name="url">
                        </div>
                    </div>
                </div>
                @if(isset($roles))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role[]"
                                       class="control-label">Roles</label>
                                <select class="select-remote form-control"
                                        multiple="multiple"
                                        id="role[]"
                                        name="role[]"
                                        style="min-height: 120px;">
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->role_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group">
                            <label for="description" class="control-label">Description</label>
                            <textarea class="form-control"
                                      placeholder="Enter Description"
                                      id="description"
                                      name="description"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@include('modules/backend/sidebar/form/script')

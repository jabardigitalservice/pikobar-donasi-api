<style>
    .tab-pan-title {
        background-color: #f47536 !important;
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
        <li class="active"><a href="#details" data-toggle="tab">Menu [{{$model->menu_title}}]</a></li>
        <div class="box-tools pull-right">
            <button type="button" id="btnAddSubmenu" class="btn btn-success btn-sm"
                    data-action="NEW" data-load-to="#menu-entry"
                    data-href="{{route("backend::sidebars.showCreateRoot", $model->id)}}">
                <i class="fa fa-plus-circle"></i> Sub Menu
            </button>
            <button type="button" id="btnEditForm" class="btn btn-primary btn-sm">
                <i class="fa fa-pencil-square"></i> Edit
            </button>
            <button type="button" id="btnSubmitForm" class="btn btn-primary btn-sm">
                Submit
            </button>
            <button type="button" id="btnCancelForm" class="btn btn-default btn-sm">
                <i class="fa fa-times-circle"></i> Cancel
            </button>
            @if($model->parent_id != '0')
                <button type="button" id="btnDelete" class="btn btn-danger btn-sm"
                        href="javascript:;"><i class="fa fa-times-circle"></i> Delete
                </button>
            @endif
        </div>
    </ul>
    <form accept-charset="utf-8" class="form-vertical" id="form-menu" method="POST"
          action="{{route("backend::sidebars.updateChildNode")}}">
        @csrf
        <input type="hidden" name="is_root" value="false"/>
        <input type="hidden" name="id" value="{{$model->id}}">
        <input type="hidden" name="parent_id" value="{{$model->parent_id}}">
        <div class="tab-content">
            <div class="tab-pane active disabled" id="details">
                <div class="tab-pan-title"> View menu [{{$model->menu_title}}]</div>

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
                                   value="{{$model->menu_title}}" disabled="">
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="form-group required">
                            <label for="slug" class="control-label">Key<sup>*</sup></label>
                            <input class="form-control"
                                   required=""
                                   placeholder="Enter Slug"
                                   id="slug" type="text"
                                   name="slug" value="{{$model->slug}}" disabled="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group required">
                            <label for="icon" class="control-label">Icon<sup>*</sup></label>
                            <input class="form-control"
                                   required=""
                                   placeholder="Enter icon"
                                   id="icon" type="text"
                                   name="icon" value="{{$model->icon}}" disabled="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="menu_order" class="control-label">Order</label>
                            <input class="form-control"
                                   placeholder="Enter Order, ex : 1"
                                   id="menu_order"
                                   type="number"
                                   name="menu_order"
                                   value="{{$model->menu_order}}" disabled="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="url" class="control-label">Url<sup>*</sup></label>
                            <input class="form-control"
                                   placeholder="Enter url"
                                   id="url"
                                   type="text"
                                   name="url"
                                   value="{{$model->url}}" disabled="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="role[]"
                                   class="control-label">Roles</label>
                            <select class="select-remote form-control"
                                    multiple="multiple"
                                    id="role[]"
                                    name="role[]"
                                    disabled="" style="min-height: 120px;">
                                @foreach($roles as $role)
                                    <option {{in_array($role->id, $listSelectedRole ?: []) ? "selected": ""}} value="{{$role->id}}">{{$role->role_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description"
                                   class="control-label">Description</label>
                            <textarea
                                    class="form-control"
                                    placeholder="Enter Description"
                                    id="description"
                                    name="description"
                                    disabled="">{{$model->description}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@include('modules/backend/sidebar/form/script')

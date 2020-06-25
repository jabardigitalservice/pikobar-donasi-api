<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Libraries\ImageLibrary;
use App\Models\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Webpatser\Uuid\Uuid;

class UserController extends BaseBackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Users';
        $this->route = $this->routes['backend'] . 'users';
        $this->slug = $this->slugs['backend'] . 'users';
        $this->view = $this->views['backend'] . 'user';
        $this->breadcrumb = '<li><a href="' . route($this->route . '.index') . '">' . $this->menu . '</a></li>';
        # share parameters
        $this->share();
    }

    public function index(Request $request)
    {
        try {
            $breadcrumb = $this->breadcrumbs($this->breadcrumb . '<li class="active">' . 'List Users' . '</li>');
            return view($this->view . '.index', compact('breadcrumb'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }
    }

    /**
     * jQuery datatable responses.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function getDatatable(Request $request)
    {
        try {
            $draw = $request->input('draw');
            $start = $request->input('start');
            $length = $request->input('length');
            $page = (int)$start > 0 ? ($start / $length) + 1 : 1;
            $limit = (int)$length > 0 ? $length : 10;
            $columnIndex = $request->input('order')[0]['column']; // Column index
            $columnName = $request->input('columns')[$columnIndex]['data']; // Column name
            $columnSortOrder = $request->input('order')[0]['dir']; // asc or desc
            $searchValue = $request->input('search')['value']; // Search value
            $conditions = '1 = 1';
            if (!empty($searchValue)) {
                $conditions .= " AND content_title LIKE '%" . strtolower(trim($searchValue)) . "%'";
            }
            $countAll = User::count();
            $paginate = User::select('*')
                ->whereRaw($conditions)
                ->orderBy($columnName, $columnSortOrder)
                ->paginate($limit, ["*"], 'page', $page);
            $items = array();
            foreach ($paginate->items() as $idx => $row) {
                $action = null;
                $routeDetail = route("backend::users.edit", $row['id']);
                $action .= '<a href="' . $routeDetail . '"><i class="fa fa-edit"></i></a>';
                //$action .= '<a onclick="deleteRow(' . $idx . ')" data-toggle="tooltip" data-placement="right" title="Delete"><input id="delete_' . $idx . '" type="hidden" value="' . $row['id'] . '"><i class="fa fa-trash" style="margin: 10px;color: #ff4d65"></i></a>';
                $items[] = array(
                    "id" => $row['id'],
                    "username" => $row['username'],
                    "email" => $row->email,
                    "role_name" => $row->roles->pluck('role_name')->implode(','),
                    "action" => $action,
                );
            }
            $response = array(
                "draw" => (int)$draw,
                "recordsTotal" => (int)$countAll,
                "recordsFiltered" => (int)$paginate->total(),
                "data" => $items
            );
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }
    }

    public function showCreate()
    {
        try {
            $breadcrumb = $this->breadcrumbs($this->breadcrumb . '<li class="active">' . 'Create User' . '</li>');
            $roles = Role::select('*')->get();
            return view($this->view . '.form.create', compact('breadcrumb', 'roles'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }
    }

    public function showProfileForm(Request $request)
    {
        try {
            $breadcrumb = $this->breadcrumbs($this->breadcrumb);
            $route = $this->route;
            return view($this->view . '.index', compact('breadcrumb', 'route'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }
    }

    public function showEdit($id)
    {
        try {
            $breadcrumb = $this->breadcrumbs($this->breadcrumb . '<li class="active">' . 'Edit User' . '</li>');
            $data = User::findOrFail($id);
            $roles = Role::select('*')->get();
            $roleSelected = $data->roles()->get();
            $listSelectedRole = array();
            foreach ($roleSelected as $g) {
                $listSelectedRole[$g->id] = $g->id;
            }
            return view($this->view . '.profile', compact('breadcrumb',
                    'data',
                    'roles',
                    'listSelectedRole')
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }
    }

    /**
     * Prefix for create or update post data.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if ($request->hidden_id == '') {
            return $this->create($request);
        } else {
            return $this->update($request, $request->input('hidden_id'));
        }
    }

    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $loggedUser = auth()->user()->roles()->pluck('roles.slug')->toArray();
            if ($loggedUser[0] === 'owner' || $loggedUser[0] === 'administrator') {
                $validator = Validator::make($request->all(), [
                    'username' => 'required|min:4|max:50|unique:users,username,NULL,id,deleted_at,NULL',
                    'email' => 'email|required|min:4|max:50|unique:users,email,NULL,id,deleted_at,NULL',
                    'first_name' => 'required|max:150',
                    'last_name' => 'required|max:150',
                    'avatar' => 'mimes:jpeg,jpg,png',
                    'password' => 'required|min:5|max:50',
                    'roles' => 'required',
                    'gender' => 'required',
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'first_name' => 'required|max:150',
                    'last_name' => 'required|max:150',
                    'avatar' => 'mimes:jpeg,jpg,png',
                    'password' => 'required|min:5|max:50',
                    'gender' => 'required',
                    'username' => 'required|min:4|max:50|unique:users,username,NULL,id,deleted_at,NULL',
                    'email' => 'email|required|min:4|max:50|unique:users,email,NULL,id,deleted_at,NULL',
                ]);
            }
            if ($validator->fails()) {
                DB::rollBack();
                return redirect()
                    ->route($this->route . '.showCreate')
                    ->withErrors($validator)
                    ->withInput();
            }
            $user = new User();
            foreach ($request->file() as $key => $file) {
                if ($request->hasFile($key)) {
                    if ($request->file($key)->isValid()) {
                        $imageId = (new ImageLibrary())->saveUserImg($request->file($key), 'images/user',
                            $request->username);
                        $user->avatar = $imageId;
                    }
                } else {
                    $key_id = !empty($request->$key . '_old') ? $request->$key . '_old' : null;
                    $user->$key = $key_id;
                }
            }
            $user->id = Uuid::generate(4)->string;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->active = 1;
            $user->password = Hash::make($request->password, ['rounds' => 10]);
            $user->created_at = date('Y-m-d');
            $user->updated_at = date('Y-m-d');
            $user->email_verified_at = date('Y-m-d');
            $user->save();
            if ($loggedUser[0] === 'owner' || $loggedUser[0] === 'administrator') {
                $roleModel = Role::where('id', $request->roles)->first();
                if ($roleModel) {
                    $tempExtra = [];
                    $tempExtra['id'] = Uuid::generate(4)->string;
                    $tempExtra['created_at'] = date('Y-m-d');
                    $tempExtra['updated_at'] = date('Y-m-d');
                    $syncRoles[$roleModel->id] = $tempExtra;
                }
                $user->roles()->sync($syncRoles);
            }
            DB::commit();
            return redirect()->route($this->route . '.showCreate')
                ->with('success', "Success update");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            abort(500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $loggedUser = auth()->user()->roles()->pluck('roles.slug')->toArray();
            $user = User::findOrFail($id);
            if ($loggedUser[0] === 'owner' || $loggedUser[0] === 'administrator') {
                $validator = Validator::make($request->all(), [
                    'first_name' => 'required|max:150',
                    'last_name' => 'required|max:150',
                    'username' => 'required|unique:users,username,' . $id . '|max:150',
                    'email' => 'required|unique:users,email,' . $id . '|max:255',
                    'roles' => 'required',
                    'gender' => 'required',
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'first_name' => 'required|max:150',
                    'last_name' => 'required|max:150',
                    'username' => 'required|unique:users,username,' . $id . '|max:150',
                    'email' => 'required|unique:users,email,' . $id . '|max:255',
                    'gender' => 'required',
                ]);
            }
            if ($validator->fails()) {
                DB::rollBack();
                return redirect()
                    ->route($this->route . '.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
            foreach ($request->file() as $key => $file) {
                if ($request->hasFile($key)) {
                    if ($request->file($key)->isValid()) {
                        $imageId = (new ImageLibrary())->saveUserImg($request->file($key), 'images/user',
                            $request->username);
                        $user->avatar = $imageId;
                    }
                } else {
                    $key_id = !empty($request->$key . '_old') ? $request->$key . '_old' : null;
                    $user->$key = $key_id;
                }
            }
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->update();
            if ($loggedUser[0] === 'owner' || $loggedUser[0] === 'administrator') {
                $roleModel = Role::where('id', $request->roles)->first();
                if ($roleModel) {
                    $tempExtra = [];
                    $tempExtra['id'] = Uuid::generate(4)->string;
                    $tempExtra['created_at'] = date('Y-m-d');
                    $tempExtra['updated_at'] = date('Y-m-d');
                    $syncRoles[$roleModel->id] = $tempExtra;
                }
                $user->roles()->sync($syncRoles);
            }
            DB::commit();
            return redirect()->route($this->route . '.edit', $id)
                ->with('success', "Success update");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            abort(500);
        }
    }

    public function removeMedia(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = User::findOrFail($id);
            $data->avatar = null;
            $data->update();
            $image = Image::findOrFail($request->image_id);
            if ($image->image_url != 'images/avatar/avatar-128x128.png') {
                Storage::disk('local')->delete('public/' . $image->image_url);
                $image->delete();
            }
            DB::commit();
            return response()->json(['message' => 'ok'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateRequest;
use App\Libraries\ImageLibrary;
use App\Mappers\UserMapper;
use App\Models\Role;
use App\Services\Mapper\Facades\Mapper;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Webpatser\Uuid\Uuid;

class UserController extends Controller
{
    private $imageModel;

    public function __construct()
    {
        $this->imageModel = new \App\Models\Image();
    }

    public function index(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'users.updated_at';
            $order = $request->has('order') ? $request->input('order') : 'DESC';
            $conditions = '1 = 1';
            if (!empty($search_term)) {
                $conditions .= " AND users.username ILIKE '%$search_term%'";
            }
            $paged = User::sql()
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);
            $countAll = User::count();
            return Mapper::list(new UserMapper(), $paged, $countAll, $request->method());
        } catch (\Exception $e) {
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function store(CreateRequest $request)
    {
        \DB::beginTransaction();
        try {
            $data = new \App\User();
            $uuid = (string)Uuid::generate(4);
            $data->id = $uuid;
            $data->username = $request->username;
            $data->email = $request->email;
            $data->first_name = $request->first_name;
            $data->last_name = $request->last_name;
            $data->gender = $request->gender;
            $data->password = Hash::make($request->password, ['rounds' => 10]);
            foreach ($request->file() as $key => $file) {
                if ($request->hasFile($key)) {
                    if ($request->file($key)->isValid()) {
                        $key_id = (new ImageLibrary())->saveUserImg($request->file($key), 'images/user',
                            $request->username);
                        $data->$key = $key_id;
                    }
                } else {
                    $key_id = !empty($request->$key . '_old') ? $request->$key . '_old' : null;
                    $data->$key = $key_id;
                }
            }
            $data->save();
            if (!empty($request->roles)) {
                $syncRoles = [];
                foreach ($request->roles as $role) {
                    $roleModel = Role::where('slug', $role)->first();
                    if ($roleModel) {
                        $tempExtra = [];
                        $tempExtra['id'] = Uuid::generate(4)->string;
                        $tempExtra['created_at'] = date('Y-m-d');
                        $tempExtra['updated_at'] = date('Y-m-d');
                        $syncRoles[$roleModel->id] = $tempExtra;
                    }
                }
                $data->roles()->sync($syncRoles);
            }
            \DB::commit();
            return Mapper::single(new UserMapper(), $data, $request->method());
        } catch (\Exception $e) {
            \DB::rollback();
            return Mapper::error($e->getMessage(), $request->method());
        }
    }
}

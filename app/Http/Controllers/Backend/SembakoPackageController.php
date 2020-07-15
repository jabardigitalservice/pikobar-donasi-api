<?php


namespace App\Http\Controllers\Backend;


use App\Http\Controllers\BaseBackendController;
use App\Libraries\PostmanLibrary as PostLib;
use App\Models\SembakoPackage;
use App\Models\SembakoPackageItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SembakoPackageController extends BaseBackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Paket Sembako';
        $this->route = $this->routes['backend'] . 'sembako-packages';
        $this->slug = $this->slugs['backend'] . 'sembako-packages';
        $this->view = $this->views['backend'] . 'sembako_package';
        $this->breadcrumb = '<li><a href="' . route($this->route . '.index') . '">' . $this->menu . '</a></li>';
        # share parameters
        $this->share();
    }

    public function index(Request $request)
    {
        try {
            $breadcrumb = $this->breadcrumbs($this->breadcrumb . '<li class="active">' . 'Daftar Paket Sembako' . '</li>');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }
        return view($this->view . '.index', compact('breadcrumb'));
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
            $countAll = SembakoPackage::count();
            $paginate = SembakoPackage::select('*')
                ->whereRaw($conditions)
                ->orderBy($columnName, $columnSortOrder)
                ->paginate($limit, ["*"], 'page', $page);
            $items = array();
            foreach ($paginate->items() as $idx => $row) {
                $action = null;
                $routeDetail = route("backend::sembako-packages.edit", $row['id']);
                $action .= '<a href="' . $routeDetail . '"><i class="fa fa-edit"></i></a>';
                $action .= '<a onclick="deleteRow(' . $idx . ')" data-toggle="tooltip" data-placement="right" 
                title="Delete"><input id="delete_' . $idx . '" type="hidden" value="' . $row['id'] . '">
                <i class="fa fa-trash" style="margin: 10px;color: #ff4d65"></i></a>';
                $items[] = array(
                    "id" => $row['id'],
                    "sku" => $row['username'],
                    "package_name" => $row->package_name,
                    "package_description" => $row->package_description,
                    "status" => $row->status,
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
            $breadcrumb = $this->breadcrumbs($this->breadcrumb . '<li class="active">' . 'Buat Paket Sembako' . '</li>');
            $sembakoItems = SembakoPackageItem::select('*')->get();
            return view($this->view . '.form.create', compact('breadcrumb', 'sembakoItems'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }
    }

    public function showDetail($id, Request $request)
    {
        try {
            $api = $this->baseUrl . "/api/v1/sembako/show/$id";
            $response = PostLib::getJson($api, $this->accessToken);
            $data = $response->body['data']['item'];
            $breadcrumb = $this->breadcrumbs($this->breadcrumb . '<li class="active">' . 'Detail Paket Sembako' . '</li>');
            return view($this->view . '.form', compact('breadcrumb', 'data'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }
    }

    public function showEdit($id)
    {
        try {
            $breadcrumb = $this->breadcrumbs($this->breadcrumb . '<li class="active">' . 'Edit Paket Sembako' . '</li>');
            $data = SembakoPackage::findOrFail($id);
            $sembakoItem = SembakoPackageItem::select('*')->get();
            $sembakoItemSelected = $data->items()->get();
            $listSelectedSembakoItem = array();
            foreach ($sembakoItemSelected as $g) {
                $listSelectedSembakoItem[$g->id] = $g->id;
            }
            return view($this->view . '.edit', compact('breadcrumb',
                    'data',
                    'sembakoItem',
                    'listSelectedSembakoItem')
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = array(
                'sku' => $request->input('sku'),
                'package_name' => $request->input('package_name'),
                'package_description' => $request->input('package_description'),
                'status' => $request->input('status'),
                'items'=>$request->input('items'),
            );
            $api = $this->baseUrl . "/api/v1/sembako/create";
            $response = PostLib::postJson($api, $this->accessToken, $data);
            if ($response->code == 200) {
                return redirect()->route('backend::sembako-packages.index')->with('success', "Sukses simpan data");
            } else {
                return redirect()
                    ->back()
                    ->withErrors($response->body['errors'][0])
                    ->withInput();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()
                ->back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function update($id, Request $request)
    {
        try {
            $data = array(
                'sku' => $request->input('sku'),
                'package_name' => $request->input('package_name'),
                'package_description' => $request->input('package_description'),
                'status' => $request->input('status'),
                'items'=>$request->input('items'),
            );
            $api = $this->baseUrl . "/api/v1/sembako/update/$id";
            $response = PostLib::postJson($api, $this->accessToken, $data);
            if ($response->code == 200) {
                return redirect()->route('backend::sembako-packages.index')->with('success', "Sukses ubah data");
            } else {
                return redirect()
                    ->back()
                    ->withErrors($response->body['errors'][0])
                    ->withInput();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()
                ->back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function delete($id){
        try {
            $api = $this->baseUrl . "/api/v1/sembako/delete/$id";
            $response = PostLib::postJson($api, $this->accessToken);
            if ($response->code == 200) {
                return redirect()->route('backend::sembako-packages.index')->with('success', "Sukses hapus data");
            } else {
                return redirect()
                    ->back()
                    ->withErrors($response->body['errors'][0])
                    ->withInput();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()
                ->back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}
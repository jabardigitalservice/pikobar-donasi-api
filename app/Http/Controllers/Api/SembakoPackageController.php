<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sembako\CreateItemRequest;
use App\Http\Requests\Sembako\CreateRequest;
use App\Http\Requests\Sembako\UpdateItemRequest;
use App\Http\Requests\Sembako\UpdateRequest;
use App\Libraries\ConstantParser;
use App\Mappers\SembakoItemPackageMap;
use App\Mappers\SembakoPackageMap;
use App\Models\Constants;
use App\Models\SembakoPackage;
use App\Models\SembakoPackageItem;
use App\Services\Mapper\Facades\Mapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Webpatser\Uuid\Uuid;

class SembakoPackageController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'sembako_packages.updated_at';
            $order = $request->has('order') ? $request->input('order') : 'DESC';
            $conditions = '1 = 1';
            if (!empty($search_term)) {
                $conditions .= " AND LOWER(sembako_packages.package_name) LIKE '%$search_term%'";
                $conditions .= " OR LOWER(sembako_packages.sku) LIKE '%$search_term%'";
            }
            $paged = SembakoPackage::sql()
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);
            $countAll = SembakoPackage::count();
            return Mapper::list(new SembakoPackageMap(), $paged, $countAll, $request->method());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function itemIndex(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'sembako_package_items.updated_at';
            $order = $request->has('order') ? $request->input('order') : 'DESC';
            $conditions = '1 = 1';
            if (!empty($search_term)) {
                $conditions .= " AND LOWER(sembako_package_items.item_name) LIKE '%$search_term%'";
                $conditions .= " OR LOWER(sembako_package_items.item_sku) LIKE '%$search_term%'";
            }
            $paged = SembakoPackageItem::sql()
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);
            $countAll = SembakoPackageItem::count();
            return Mapper::list(new SembakoItemPackageMap(), $paged, $countAll, $request->method());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function store(CreateRequest $request)
    {
        \DB::beginTransaction();
        try {
            $sembako = SembakoPackage::create([
                'id' => Uuid::generate(4)->string,
                'sku' => $request->sku,
                'package_name' => $request->package_name,
                'package_description' => $request->package_description,
                'status' => !$request->status ? false : true,
                'last_modified_by' => auth('api')->user()->id
            ]);
            if (!empty($request->items)) {
                $syncMaterials = [];
                foreach ($request->items as $material) {
                    if (array_key_exists('id', $material)) {
                        $materialModel = SembakoPackageItem::findOrFail($material['id']);
                        $tempExtra = [];
                        $tempExtra['id'] = Uuid::generate(4)->string;
                        $tempExtra['created_at'] = date('Y-m-d');
                        $tempExtra['updated_at'] = date('Y-m-d');
                        $syncMaterials[$materialModel->id] = $tempExtra;
                    }
                }
                $sembako->items()->sync($syncMaterials);
            }
            \DB::commit();
            return Mapper::single(new SembakoPackageMap(), $sembako, $request->method());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            \DB::rollBack();
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function itemStore(CreateItemRequest $request)
    {
        \DB::beginTransaction();
        try {
            $uomId = ConstantParser::searchById($request->uom, Constants::UOM);
            if (!$uomId) {
                return Mapper::error("UOM tidak tersedia.", $request->method());
            }
            $sembako = SembakoPackageItem::create([
                'id' => Uuid::generate(4)->string,
                'item_name' => $request->item_name,
                'item_sku' => $request->item_sku,
                'quantity' => $request->quantity,
                'uom' => $uomId['id'],
                'uom_name' => $uomId['name'],
                'package_description' => $request->package_description,
                'status' => !$request->status ? false : true,
                'last_modified_by' => auth('api')->user()->id
            ]);
            \DB::commit();
            return Mapper::single(new SembakoPackageMap(), $sembako, $request->method());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            \DB::rollBack();
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function itemUpdate($id, UpdateItemRequest $request)
    {
        \DB::beginTransaction();
        try {
            $sembako = SembakoPackageItem::findOrFail($id);
            $uomId = ConstantParser::searchById($request->uom, Constants::UOM);
            if (!$uomId) {
                return Mapper::error("UOM tidak tersedia.", $request->method());
            }
            $sembako->item_name = $request->item_name;
            //SKU seharusnya memang tidak boleh diupdate, karena akan mengacaukan data
            //$sembako->item_sku = $request->item_sku;
            $sembako->quantity = $request->quantity;
            $sembako->package_description = $request->package_description;
            $sembako->status = !$request->status ? false : true;
            $sembako->uom = $uomId['id'];
            $sembako->uom_name = $uomId['name'];
            $sembako->last_modified_by = auth('api')->user()->id;
            $sembako->update();
            \DB::commit();
            return Mapper::single(new SembakoItemPackageMap(), $sembako, $request->method());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            \DB::rollBack();
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function update($id, UpdateRequest $request)
    {
        \DB::beginTransaction();
        try {
            $sembako = SembakoPackage::findOrFail($id);
            //SKU seharusnya memang tidak boleh diupdate, karena akan mengacaukan data
            //$sembako->sku = $request->sku;
            $sembako->package_name = $request->package_name;
            $sembako->package_description = $request->package_description;
            $sembako->status = !$request->status ? false : true;
            $sembako->last_modified_by = auth('api')->user()->id;
            $sembako->update();
            if (!empty($request->items)) {
                $syncMaterials = [];
                foreach ($request->items as $material) {
                    if (array_key_exists('id', $material)) {
                        $materialModel = SembakoPackageItem::findOrFail($material['id']);
                        $tempExtra = [];
                        $tempExtra['id'] = Uuid::generate(4)->string;
                        $tempExtra['created_at'] = date('Y-m-d');
                        $tempExtra['updated_at'] = date('Y-m-d');
                        $syncMaterials[$materialModel->id] = $tempExtra;
                    }
                }
                $sembako->items()->sync($syncMaterials);
            }
            \DB::commit();
            return Mapper::single(new SembakoPackageMap(), $sembako, $request->method());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            \DB::rollBack();
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function destroyItem($id, Request $request)
    {
        \DB::beginTransaction();
        try {
            $item = SembakoPackageItem::findOrFail($id);
            $item->deleted_by = auth('api')->user()->id;
            $item->update();
            $item->delete();
            \DB::commit();
            return Mapper::success($request->method());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            \DB::rollBack();
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function destroy($id, Request $request)
    {
        \DB::beginTransaction();
        try {
            $item = SembakoPackage::findOrFail($id);
            $item->deleted_by = auth('api')->user()->id;
            $item->update();
            $item->items()->detach();
            $item->delete();
            \DB::commit();
            return Mapper::success($request->method());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            \DB::rollBack();
            return Mapper::error("item tidak ada", $request->method());
        }
    }
}
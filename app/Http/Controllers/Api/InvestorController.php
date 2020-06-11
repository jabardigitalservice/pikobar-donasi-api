<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Investor\CreateRequest;
use App\Libraries\ConstantParser;
use App\Libraries\FilesLibrary;
use App\Libraries\ImageLibrary;
use App\Mappers\Investor\SembakoDonateMap;
use App\Models\Bank;
use App\Models\Constants;
use App\Models\Investor;
use App\Models\InvestorItem;
use App\Models\SembakoPackage;
use App\Services\Mapper\Facades\Mapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Webpatser\Uuid\Uuid;

class InvestorController extends ApiController
{
    public function store(CreateRequest $request)
    {
        //investor category
        $investorCategoryId = ConstantParser::searchById($request->category_id, Constants::INVESTOR_CATEGORIES);

        //logistik,medis,etc
        $donateCategoryId = ConstantParser::searchById($request->donate_id, Constants::DONATION_CATEGORIES);

        //pending,verified
        $donateStatus = ConstantParser::searchBySlug('pending', Constants::INVESTOR_STATUS);

        $investor_name = $request->investor_name;
        $phone = $request->phone;
        $email = $request->email;

        DB::beginTransaction();
        try {
            $data = new Investor();
            $data->id = Uuid::generate(4)->string;
            $data->investor_name = $investor_name;
            $data->category_id = $investorCategoryId['id'];
            $data->category_slug = $investorCategoryId['slug'];
            $data->category_name = $investorCategoryId['name'];
            $data->phone = $phone;
            $data->email = $email;
            $data->address = $request->address;
            $data->donate_id = $donateCategoryId['id'];
            $data->donate_category = $donateCategoryId['slug'];
            $data->donate_category_name = $donateCategoryId['name'];
            $data->show_name = !$request->show_name ? false : true;
            $data->donate_status = $donateStatus['slug'];
            $data->donate_status_name = $donateStatus['name'];
            $data->donate_date = date('Y-m-d');
            $data->invoice_number = \App\Libraries\NumberLibrary::createInvoice();
            $dataRequest = null;
            if ($donateCategoryId['slug'] === 'logistik') {
                try {
                    foreach ($request->file() as $key => $file) {
                        if ($request->hasFile($key)) {
                            if ($request->file($key)->isValid()) {
                                $fileLib = new FilesLibrary();
                                $name = $investor_name . '-' . Str::random(5) . '-' . date('Y-m-d H:i:s');
                                $dataId = $fileLib->saveDocument($request->file($key), $name);
                                $tempExtra = [];
                                $tempExtra['id'] = Uuid::generate(4)->string;
                                $tempExtra['created_at'] = date('Y-m-d');
                                $tempExtra['updated_at'] = date('Y-m-d');
                                $imageData[$dataId] = $tempExtra;
                            }
                        } else {
                            $key_id = !empty($request->$key . '_old') ? $request->$key . '_old' : null;
                            $imageData[$key_id] = array();
                        }
                    }
                    $data->save();
                    $dataRequest = $this->storeSembako($request, $data->id);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    return Mapper::error($e->getMessage(), $request->method());
                }
            } else if ($donateCategoryId['slug'] === 'tunai') {
                try {
                    foreach ($request->file() as $key => $file) {
                        if ($request->hasFile($key)) {
                            if ($request->file($key)->isValid()) {
                                $fileLib = new ImageLibrary();
                                $savePath = 'investor/transfer';
                                $name = $investor_name . '-' . Str::random(5) . '-' . date('Y-m-d H:i:s');
                                $dataId = $fileLib->saveTransferSlip($request->file($key), $savePath, $name);
                                $tempExtra = [];
                                $tempExtra['id'] = Uuid::generate(4)->string;
                                $tempExtra['created_at'] = date('Y-m-d');
                                $tempExtra['updated_at'] = date('Y-m-d');
                                $imageData[$dataId] = $tempExtra;
                            }
                        } else {
                            $key_id = !empty($request->$key . '_old') ? $request->$key . '_old' : null;
                            $imageData[$key_id] = array();
                        }
                    }
                    $data->save();
                    $dataRequest = $this->storeTunai($request, $data->id);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    return Mapper::error($e->getMessage(), $request->method());
                }
            }
            return Mapper::single(new SembakoDonateMap(), $dataRequest, $request->method());
        } catch (\Exception $e) {
            DB::rollBack();
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    /**
     * Jika donasinya adalah paket yang telah disediakan.
     *
     * @param CreateRequest $request
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    private function storeSembako(CreateRequest $request, $id)
    {
        $sembakoPackage = SembakoPackage::find($request->package_id);
        if (!$sembakoPackage) {
            throw new \Exception("Invalid SembakoPackage Id");
        }
        $data = InvestorItem::create([
            'id' => Uuid::generate(4)->string,
            'investor_id' => $id,
            'investor_name' => $request->investor_name,
            'investor_phone' => $request->phone,
            'investor_email' => $request->email,
            'donate_category' => 'logistik',
            'item_package_id' => $sembakoPackage->id,
            'item_package_sku' => $sembakoPackage->sku,
            'item_package_name' => $sembakoPackage->package_name,
            'quantity' => $request->quantity,
        ]);
        return $data;
    }

    /**
     * Jika donasinya adalah berupa uang tunai.
     *
     * @param CreateRequest $request
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    private function storeTunai(CreateRequest $request, $id)
    {
        $bank = Bank::find($request->bank_id);
        if (!$bank) {
            throw new \Exception("Invalid Bank Id");
        }
        $data = InvestorItem::create([
            'id' => Uuid::generate(4)->string,
            'investor_id' => $id,
            'investor_name' => $request->investor_name,
            'investor_phone' => $request->phone,
            'investor_email' => $request->email,
            'donate_category' => 'tunai',
            'bank_id' => $bank->id,
            'bank_account' => $request->bank_account,
            'bank_number' => $request->bank_number,
            'amount' => $request->amount,
        ]);
        return $data;
    }

    /**
     * Jika donasinya adalah berupa barang barang medis (inputan bebas).
     *
     * @param Request $request
     */
    private function storeMedis(Request $request)
    {

    }
}
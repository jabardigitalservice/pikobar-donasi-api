<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Investor\CreateRequest;
use App\Libraries\ConstantParser;
use App\Mappers\Investor\SembakoDonateMap;
use App\Models\Constants;
use App\Models\Investor;
use App\Models\InvestorItem;
use App\Models\SembakoPackage;
use App\Services\Mapper\Facades\Mapper;
use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;

class InvestorController extends ApiController
{
    //@todo nomor invoice belum
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

        $data = Investor::create([
            'id' => Uuid::generate(4)->string,
            'investor_name' => $investor_name,
            'category_id' => $investorCategoryId['id'],
            'category_slug' => $investorCategoryId['slug'],
            'category_name' => $investorCategoryId['name'],
            'phone' => $phone,
            'email' => $email,
            'address' => $request->address,
            'donate_id' => $donateCategoryId['id'],
            'donate_category' => $donateCategoryId['slug'],
            'donate_category_name' => $donateCategoryId['name'],

            'donate_status' => $donateStatus['slug'],
            'donate_status_name' => $donateStatus['name'],

            'invoice_number' => (string)now(),
            'attachment_id' => null,

            'show_name' => !$request->show_name ? false : true,
            'donate_date' => date('Y-m-d'),
        ]);

        $dataRequest = null;
        if ($donateCategoryId['slug'] === 'logistik') {
            try {
                $dataRequest = $this->storeSembako($request, $data->id);
            } catch (\Exception $e) {
                return Mapper::error($e->getMessage(), $request->method());
            }
        } else if ($donateCategoryId['slug'] === 'tunai') {
            try {
                $dataRequest = $this->storeTunai($request, $data->id);
            } catch (\Exception $e) {
                return Mapper::error($e->getMessage(), $request->method());
            }
        }
        return Mapper::single(new SembakoDonateMap(), $dataRequest, $request->method());
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
        $sembakoPackage = SembakoPackage::findOrFail($request->package_id);

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
        $data = InvestorItem::create([
            'id' => Uuid::generate(4)->string,
            'investor_id' => $id,
            'investor_name' => $request->investor_name,
            'investor_phone' => $request->phone,
            'investor_email' => $request->email,
            'donate_category' => 'tunai',
            'bank_id' => $request->bank_id,
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
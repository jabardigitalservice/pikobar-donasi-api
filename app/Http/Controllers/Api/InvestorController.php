<?php

namespace App\Http\Controllers\Api;

use App\Events\NewInvestorAwardEvent;
use App\Events\NewInvestorEvent;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Investor\CreateRequest;
use App\Http\Requests\Investor\VerificationRequest;
use App\Libraries\ConstantParser;
use App\Libraries\FilesLibrary;
use App\Libraries\ImageLibrary;
use App\Mappers\Investor\InvestorMapper;
use App\Mappers\Investor\ListInvestorSembako;
use App\Mappers\Investor\ListInvestorTunai;
use App\Mappers\Investor\SembakoDonateMap;
use App\Models\Bank;
use App\Models\Constants;
use App\Models\Investor;
use App\Models\InvestorItem;
use App\Models\SembakoPackage;
use App\Services\Mapper\Facades\Mapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Webpatser\Uuid\Uuid;

/**
 * Class InvestorController.
 *
 * @package App\Http\Controllers\Api
 */
class InvestorController extends ApiController
{
    public function index(Request $request)
    {
        try {
            $limit = $request->has('limit') ? $request->input('limit') : 100;
            $sort = $request->has('sort') && $request->input('sort') !== null ? $request->input('sort') : 'investors.donate_date';
            $order = $request->has('order') && $request->input('order') !== null ? $request->input('order') : 'DESC';
            $type = $request->has('type') && $request->input('type') !== null ? $request->input('type') : 'tunai';
            $donateStatus = $request->has('donate_status') && $request->input('donate_status') !== null ? $request->input('donate_status') : '';
            $investorName = $request->has('investor_name') && $request->input('investor_name') !== null ? $request->input('investor_name') : '';
            $conditions = '1 = 1';
            //tipe donasi tunai,logistik,medis
            $conditions .= " AND donate_category = '$type'";
            if ($donateStatus) {
                $conditions .= " AND donate_status = '$donateStatus'";
            }
            if ($investorName) {
                $conditions .= " AND investor_name LIKE '%" . strtolower(trim($investorName)) . "%'";
            }
            $paged = Investor::select('*')
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);
            $countAll = Investor::count();
            if ($type === 'tunai') {
                return Mapper::list(new ListInvestorTunai(), $paged, $countAll, $request->method());
            } else if ($type === 'logistik') {
                return Mapper::list(new ListInvestorSembako(), $paged, $countAll, $request->method());
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    /**
     * Get Investor detail, Only For Admin.
     * ini dipergunakan oleh admin untuk approval donatur,
     * yang nantinya akan ditransfer ke table warehouse (quantity langsung dihitung berdasarkan item).
     *
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function showInvestor($id, Request $request)
    {
        try {
            $item = Investor::find($id);
            if (!$item) {
                throw new \Exception("Invalid Investor Id");
            }
            return Mapper::single(new InvestorMapper(), $item, $request->method());
        } catch (\Exception $e) {
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function storeClaimAward($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $item = Investor::find($id);
            if (!$item) {
                throw new \Exception("Invalid Investor Id");
            }
            if ($item->donate_status !== 'verified') {
                throw new \Exception("Cannot send reward");
            }
            $item->award_claim = 1;
            $item->update();
            // Create an event
            event(new NewInvestorAwardEvent($item));
            // Commit to database
            DB::commit();
            return Mapper::single(new InvestorMapper(), $item, $request->method());
        } catch (\Exception $e) {
            DB::rollBack();
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function verification($id, VerificationRequest $request)
    {
        DB::beginTransaction();
        try {
            $donateStatus = ConstantParser::searchById($request->donate_status_id,
                Constants::INVESTOR_STATUS);
            $item = Investor::find($id);
            if (!$item) {
                DB::rollBack();
                throw new \Exception("Invalid Investor Id");
            }
            // jika status sudah verifikasi dan mau di unverified...
            if ($item->donate_status === 'verified' && $donateStatus['slug'] !== 'rejected') {
                DB::rollBack();
                throw new \Exception("Tidak bisa di Un Verified");
            }
            $item->donate_status = $donateStatus['slug'];
            $item->donate_status_name = $donateStatus['name'];
            $item->update();
            // Create an event
            event(new NewInvestorAwardEvent($item));
            DB::commit();
            return Mapper::single(new InvestorMapper(), $item, $request->method());
        } catch (\Exception $e) {
            DB::rollBack();
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function store(CreateRequest $request)
    {
        //investor category
        $investorCategoryId = ConstantParser::searchById($request->category_id, Constants::INVESTOR_CATEGORIES);

        //logistik,medis,etc
        $donateCategoryId = ConstantParser::searchById($request->donate_id, Constants::DONATION_CATEGORIES);

        //not_verified,verified,reject
        $donateStatus = ConstantParser::searchBySlug('not_verified', Constants::INVESTOR_STATUS);

        $investor_name = $request->investor_name;
        $phone = $request->phone;
        $email = $request->email;

        DB::beginTransaction();
        try {
            $data = new Investor();
            $data->id = (string)Uuid::generate(4)->string;
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
            if ($investorCategoryId['slug'] === 'perusahaan') {
                if ($request->hasFile('profile_picture')) {
                    if ($request->file('profile_picture')->isValid()) {
                        $imgLib = new ImageLibrary();
                        $name = $investor_name . '-' . Str::random(5) . '-' . date('Y-m-d H:i:s');
                        $fullPath = $imgLib->saveInvestorImg($request->file('profile_picture'), $name);
                        $data->profile_picture = $fullPath;
                    }
                }
            }
            if ($donateCategoryId['slug'] === 'logistik') {
                try {
                    if ($request->hasFile('files')) {
                        if ($request->file('files')->isValid()) {
                            $fileLib = new FilesLibrary();
                            $name = $investor_name . '-' . Str::random(5) . '-' . date('Y-m-d H:i:s');
                            $dataId = $fileLib->saveDocument($request->file('files'), $name);
                            $data->attachment_id = $dataId;
                        }
                    }
                    $data->save();

                    //save sembako
                    $this->storeSembako($request, $data->id);
                    // Commit to database
                    DB::commit();

                    // Create an event
                    event(new NewInvestorEvent($data));

                } catch (\Exception $e) {
                    DB::rollBack();
                    return Mapper::error($e->getMessage(), $request->method());
                }
            } else if ($donateCategoryId['slug'] === 'tunai') {
                try {
                    if ($request->hasFile('files')) {
                        if ($request->file('files')->isValid()) {
                            $fileLib = new FilesLibrary();
                            $name = $investor_name . '-' . Str::random(5) . '-' . date('Y-m-d H:i:s');
                            $dataId = $fileLib->saveTransferSlip($request->file('files'), $name);
                            $data->attachment_id = $dataId;
                        }
                    }
                    $data->save();

                    $this->storeTunai($request, $data->id);

                    // Commit to database
                    DB::commit();
                    // Create an event
                    event(new NewInvestorEvent($data));

                } catch (\Exception $e) {
                    DB::rollBack();
                    return Mapper::error($e->getMessage(), $request->method());
                }
            } else if ($donateCategoryId['slug'] === 'medis') {
                try {
                    if ($request->hasFile('files')) {
                        if ($request->file('files')->isValid()) {
                            $fileLib = new FilesLibrary();
                            $name = $investor_name . '-' . Str::random(5) . '-' . date('Y-m-d H:i:s');
                            $dataId = $fileLib->saveDocument($request->file('files'), $name);
                            $data->attachment_id = $dataId;
                        }
                    }
                    $data->save();

                    $this->storeMedis($request, $data->id);

                    // Commit to database
                    DB::commit();
                    // Create an event
                    event(new NewInvestorEvent($data));
                } catch (\Exception $e) {
                    DB::rollBack();
                    return Mapper::error($e->getMessage(), $request->method());
                }
            }
            //return response()->json(['messages'=> 'oke']);
            return Mapper::single(new SembakoDonateMap(), $data, $request->method());
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
     * @param $id
     * @return array
     * @throws \Exception
     */
    private function storeMedis(Request $request, $id)
    {
        if (!empty($request->items)) {
            $syncItems = [];
            foreach ($request->items as $item) {
                if (array_key_exists('id', $item)
                    && array_key_exists('quantity', $item)
                    && array_key_exists('package_name', $item)
                    && array_key_exists('uom', $item)
                ) {
                    $uomId = ConstantParser::searchById($item['uom'],
                        Constants::UOM);
                    $syncItems[$item['id']] = InvestorItem::create([
                        'id' => Uuid::generate(4)->string,
                        'investor_id' => $id,
                        'investor_name' => $request->investor_name,
                        'investor_phone' => $request->phone,
                        'investor_email' => $request->email,
                        'donate_category' => 'medis',
                        'item_package_id' => $item['id'],
                        'item_package_sku' => Str::slug($item['package_name']),
                        'item_package_name' => $item['package_name'],
                        'item_uom_slug' => $uomId['slug'],
                        'item_uom_name' => $uomId['name'],
                        'quantity' => $item['quantity'],
                    ]);
                }
            }
            return $syncItems;
        }
    }
}
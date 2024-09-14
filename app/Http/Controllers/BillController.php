<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountCalculation;
use App\Activity;
use App\Bill;
use App\BillBack;
use App\BillBackDetail;
use App\BillDetail;
use App\BillMessage;
use App\BillPrint;
use App\Device;
use App\Product;
use App\ProductMove;
use App\RelationProductUnit;
use App\Rules\valid_negative_price;
use App\Rules\valid_price;
use App\Rules\valid_qte;
use App\SaleMakeQteDetails;
use App\Setting;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type, Request $r)//type = 0 for buy,1 for sale,2 for offer price
    {
        //
        //check power
        if ($type != 0 && $type != 1 && $type != 2) {
            return back();
        }
        $checkPower = ($type == 0 ? Auth::user()->allow_manage_bill_buy : ($type == 1 ? Auth::user()->allow_manage_bill_sale : Auth::user()->allow_manage_bill_sale_with_profit));
        $checkPower = Auth::user()->type == 1 ? true : $checkPower;
        if ($checkPower == false) {
            $activty = new Activity();
            $activty->data = 'قام المستخدم ' . Auth::user()->name . ' بمحاولة فتح صفحة غير مصرح بدخولها باسم ' .
                ($type == 0 ? 'إدارة فواتير الشراء' : ($type == 1 ? 'إدارة فواتير البيع' : 'إدارة فواتير البيع مع عرض الأرباح')) .
                (Auth::user()->log_out_security ? 'وتم عمل تسجيل خروج له' : '');

            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->type = 0;
            $activty->notification = 1;
            $activty->save();

            if (Auth::user()->log_out_security) {
                $r->session()->invalidate();
                return redirect(\route('login'));
            } else {
                Session::flash('fault', 'غير مصرح لك بفتح هذة الصفحة !');
                return redirect(route('home'));
            }
        }
        //end check power

        $account = ($type == 0) ? (Account::where('is_supplier', 1)->orderby('name')->get()) : (Account::where('is_customer', 1)->orderby('name')->get());
        $show_profit = false;
        return view('bills.index', [
            'type' => $type,
            'accounts' => $account,
            'bill_id' => isset($r->bill_id) ? $r->bill_id : '',
            'show_profit' => (isset($r->show_profit) && $type == 1) || ($show_profit && $type == 1) ? '1' : '0',
            'setting' => Setting::first(),
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type, Request $r)//type = 0 for buy,1 for sale,2 for offer price
    {
        //check power
        if ($type != 0 && $type != 1 && $type != 2) {
            return back();
        }
        $checkPower = ($type == 0 ? Auth::user()->allow_create_bill_buy : Auth::user()->allow_create_bill_sale);
        $checkPower = Auth::user()->type == 1 ? true : $checkPower;

        if ($checkPower == false) {
            $activty = new Activity();
            $activty->data = 'قام المستخدم ' . Auth::user()->name . ' بمحاولة فتح صفحة غير مصرح بدخولها باسم ' .
                ($type == 0 ? ' إنشاء فاتورة شراء ' : 'إنشاء فاتورة بيع ') .
                (Auth::user()->log_out_security ? 'وتم عمل تسجيل خروج له' : '');

            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->type = 0;
            $activty->notification = 1;
            $activty->save();

            if (Auth::user()->log_out_security) {
                $r->session()->invalidate();
                return redirect(\route('login'));
            } else {
                Session::flash('fault', 'غير مصرح لك بفتح هذة الصفحة !');
                return redirect(route('home'));
            }
        }
        //end check power

        $account = ($type == 0) ? (Account::where('is_supplier', 1)->orderby('name')->get()) : (Account::where('is_customer', 1)->orderby('name')->get());
        $devise_stokes = Device::with('allowedStoke')->where('id', Auth::user()->device_id)->first();
        $products = ($type == 0) ? (Product::where('allow_buy', 1)->where('state', 1)->orderby('name')->get()) : (Product::where('allow_sale', 1)->where('state', 1)->orderby('name')->get());
        $device = Auth::user()->device;
        $print_design = ($type == 2) ? BillPrint::where('id', ($device->design_bill_print ? $device->design_bill_print : 1))->first() : '';
        return view('bills.create', [
            'type' => $type,
            'accounts' => $account,
            'devise_stokes' => $devise_stokes,
            'products' => $products,
            'setting' => Setting::first(),
            'bill_messages' => BillMessage::where('state', 1)->orderby('name')->get(),
            'print_design' => $print_design
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate new account
        $s = Setting::first();
        if ($request->account_id == -1) {
            $allow_account_without_tel = '';
            $allow_repeat_tell_account = '';
            $allow_account_with_negative_account = '';
            $allow_repeat_name = '';
            if (!$s->allow_account_without_tel) {
                $allow_account_without_tel = '|required';
            }
            if (!$s->allow_repeat_tell_account) {
                $allow_repeat_tell_account = '|unique:accounts,tel';
            }
            if (!$s->allow_account_with_negative_account && $request->new_account_old_account != 0) {
                $allow_account_with_negative_account = 'gt:0';
            }

            if (!$s->allow_repeat_customer_name && $request->is_customer == 1) {
                $temp = Account::where('name', $request->new_account_name)->where('is_customer', 1)->first();
                if ($temp) {
                    $allow_repeat_name = '|unique:accounts,name';
                }
            }

            if (!$s->allow_repeat_supplier_name && $request->is_supplier == 1) {
                $temp = Account::where('name', $request->new_account_name)->where('is_supplier', 1)->first();
                if ($temp) {
                    $allow_repeat_name = '|unique:accounts,name';
                }
            }
            $request->validate([
                'is_supplier' => 'boolean',
                'is_customer' => 'boolean',
            ]);
            $validator = Validator::make($request->all(), [
                'new_account_name' => 'required|max:250' . $allow_repeat_name,
                'new_account_tel' => 'max:250' . $allow_account_without_tel . $allow_repeat_tell_account,
                'new_account_old_account' => [new valid_negative_price, $allow_account_with_negative_account],
            ]);
            if (count($validator->errors()) > 0) {
                $error = 'حصل خصاء فى العملية ';
                if (isset($validator->errors()->get('*')['new_account_name'])) {
                    $error .= '<br/>';
                    $error .= ' إسم الشخص خاطى قد يكون هذا الإسم مستخدم من قبل ';
                }
                if (isset($validator->errors()->get('*')['new_account_tel'])) {
                    $error .= '<br/>';
                    $error .= 'رقم هاتف العميل خاطى قد يكون هذا الرقم مستخدم من قبل ';
                }
                if (isset($validator->errors()->get('*')['new_account_old_account'])) {
                    $error .= '<br/>';
                    $error .= 'قيمة الحساب السابق خاطئة ';
                }
                return $error;
            }
        }

        //validate exist account
        if ($request->account_id > 0) {
            $validator = '';
            $validator = Validator::make($request->all(), [
                'account_id' => 'required|exists:accounts,id',
            ]);
            if (count($validator->errors()) > 0) {
                $error = 'خطاء فى الشخص صاحب الفاتورة ';
                return $error;
            }
        }

        //validate if account in no account (account_id=0)
        if ($request->account_id == 0) {
            if ($request->total_price != $request->total_paid) {
                return 'حصل خطاء فى العملية يجب أن يتم دفع المبلغ بالكامل إذا كانت الفاتورة بدون شخص';
            }
        }

        //validate other data
        $validator = '';
        $validator = Validator::make($request->all(), [
            'bill_details.*.0' => 'required|exists:products,id',//product_id
            'bill_details.*.1' => 'required|numeric',//unit_id ,0 for main unit
            'bill_details.*.2' => ['gt:0', new valid_qte],// qte
            'bill_details.*.3' => ['gt:-1', new valid_price],//price

            'discount' => ['gt:-1', new valid_price],
            'total_price' => ['gt:0', new valid_price],//total price after discount
            'total_paid' => ['gt:-1', new valid_price],
            'message' => 'max:150',
        ]);
        if (count($validator->errors()) > 0) {
            $error = 'حصل خصاء فى العملية ';

            if (isset($validator->errors()->get('*')['total_price'])) {
                $error .= '<br/>';
                $error .= 'خطاء فى إجمالى الفاتورة ';
            }
            if (isset($validator->errors()->get('*')['discount'])) {
                $error .= '<br/>';
                $error .= 'خطاء فى الخصم ';
            }
            if (isset($validator->errors()->get('*')['total_paid'])) {
                $error .= '<br/>';
                $error .= 'خطاء فى المبلغ المدفوع ';
            }
            if (isset($validator->errors()->get('*')['message'])) {
                $error .= '<br/>';
                $error .= 'خطاء فى رسالة الفاتورة ';
            }
            if ($error == 'حصل خصاء فى العملية ') {
                $error .= 'خطاء فى المنتجات فى الفاتورة';
            }
            return $error;
        }

        //check if money in treasury not less than price_paid when typ bill is buy
        //if type is bill buy
        $device = Device::where('id', Auth::user()->device_id)->first();
        if ($request->type == 0) {//bill buy
            if ($device['treasury_value'] < $request->total_paid) {
                return 'المال فى الدرج غير كافى لدفع المبلغ المراد دفعة فى الفاتورة حيث المال فى الدرج ' . $device['treasury_value'] . ' ج ' . ' والمبلغ المراد دفعة ' .
                    round($request->total_paid, 2) . ' ج';
            }
        }

        //variable to store billtype 0 =>for bill buy,1 =>for bill sale, 2 for show
        $billType = $request->type;

        DB::beginTransaction();
        try {
            $activity_bill = new Activity();
            $activity_bill->user_id = Auth::user()->id;
            $activity_bill->device_id = Auth::user()->device_id;

            //add account if new account (buy or sale)
            $account = '';
            if ($request->account_id == -1) {
                $account = new Account();
                $account->device_id = Auth::user()->device_id;
                $account->user_id = Auth::user()->id;
                $account->name = $request->new_account_name;
                if ($request->new_account_tel)
                    $account->tel = $request->new_account_tel;
                $account->account = $request->new_account_old_account;
                $account->is_supplier = $request->is_supplier == 1 ? 1 : 0;
                $account->is_customer = $request->is_customer == 1 ? 1 : 0;
                $account->save();

                if ($account->account != 0) {
                    $nr = new AccountCalculation();
                    $nr->user_id = Auth::user()->id;
                    $nr->account_id = $account->id;
                    $nr->value = $account->account;
                    $nr->rent = abs($account->account);
                    $nr->type = 0;
                    $nr->relation_account = $nr->value > 0 ? 1 : ($nr->value < 0 ? 2 : 0);
                    $nr->device_id = Auth::user()->device_id;
                    $nr->account_after_this_action = $account->account;
                    $nr->save();
                }

                $type = $request->is_supplier == 1 ? 'مورد' : '';
                $type = $type . ($request->is_customer == 1 ? ' عميل ' : '');

                $activity = new Activity();
                $activity->user_id = Auth::user()->id;
                $activity->device_id = Auth::user()->device_id;
                $activity->data = 'اضافة ' . $type . ' جديد عند إضافة فاتورة باسم ' . $request->new_account_name . ' بحساب ' . $request->new_account_old_account . ' ج ';
                $activtyType = $request->is_supplier == 1 ? '5' : '';
                $activtyType = $request->is_customer == 1 ? '6' : $activtyType;
                $activtyType = $request->is_supplier && $request->is_customer ? '7' : $activtyType;
                $activity->type = $activtyType;
                if (Auth::user()->type != 1 &&
                    Auth::user()->create_notification_when_add_account_with_old_account &&
                    $account->account != 0) {
                    $activity->notification = 1;
                }
                $activity->save();
            }

            //variable to store bill
            $bill = new Bill();

            //add bill data (buy or sale)
            $bill->user_id = Auth::user()->id;
            $bill->device_id = Auth::user()->device_id;
            $bill->account_id = ($request->account_id == '0' ? null : ($account == '' ? $request->account_id : $account->id));
            $bill->stoke_id = $request->stoke_id;
            $bill->discount = $request->discount;
            $bill->total_price = $request->total_price;
            $bill->total_paid = $request->total_paid;
            $bill->message = isset($request->message) ? $request->message : ' ';
            $bill->type = $billType;
            $bill->save();


            //update account and treasury and validate treasury data if account data is change (buy or sale)
            if ($request->total_price != $request->total_paid) {
                //update treasury
                if ($billType == 0) {//bill buy
                    $device->treasury_value -= $request->total_paid;
                    $device->save();

                    $activity_bill->relation_treasury = 2;
                    $activity_bill->treasury_value = $request->total_paid;

                    //update account
                    if ($account == '') {
                        $account = Account::find($request->account_id);
                    }
                    $account->account += ($request->total_price - $request->total_paid);
                    $account->save();

                } else {//bill sale
                    $device->treasury_value += $request->total_paid;
                    $device->save();

                    $activity_bill->relation_treasury = 1;
                    $activity_bill->treasury_value = $request->total_paid;

                    //update account
                    if ($account == '') {
                        $account = Account::find($request->account_id);
                    }
                    if ($account->is_supplier) {//bill sale for supplier
                        $account->account -= ($request->total_price - $request->total_paid);
                    } else {
                        $account->account += ($request->total_price - $request->total_paid);
                    }
                    $account->save();

                }

            } else {
                //update treasury
                if ($billType == 0) {//bill buy
                    $device->treasury_value -= $request->total_paid;
                    $device->save();
                } else {
                    $device->treasury_value += $request->total_paid;
                    $device->save();
                }
            }

            //add details for bill and update stoke
            for ($i = 0; $i < count($request->bill_details); $i++) {
                $detailsProduct = Product::find($request->bill_details[$i][0]);
                $detailsProductRelationUnit = ($request->bill_details[$i][1] == 0 ? null : RelationProductUnit::find($request->bill_details[$i][1]));
                $detailsUnitId = ($request->bill_details[$i][1] == 0 ? $detailsProduct->product_unit_id : $detailsProductRelationUnit->product_unit_id);
                $detailsRelationQte = ($request->bill_details[$i][1] == 0 ? 1 : $detailsProductRelationUnit->relation_qte);
                $qteByMainUnit = $request->bill_details[$i][2] * $detailsRelationQte;
                $priceForMainUnit = $request->bill_details[$i][3] / $detailsRelationQte;


                //update product price if price change and setting is edit price automatic (buy and sale)
                if ($billType == 0 && $s->auto_update_price_product_bill_buy) {//(for bill buy)
                    if ($request->bill_details[$i][1] == 0) {//if product qte is main qte
                        if ($request->bill_details[$i][3] != $detailsProduct->price_buy) {
                            $detailsProduct->price_buy = $request->bill_details[$i][3];
                            $detailsProduct->save();
                        }
                    } else {//if other unit
                        if ($request->bill_details[$i][3] != $detailsProductRelationUnit->price_buy) {
                            $detailsProductRelationUnit->price_buy = $request->bill_details[$i][3];
                            $detailsProductRelationUnit->save();
                        }
                    }
                }
                if ($billType == 1 && $s->auto_update_price_product_bill_sale) {//(for bill sale)
                    if ($request->bill_details[$i][1] == 0) {//if product qte is main unit
                        if (isset($request->price_type)) {
                            if ($request->price_type == 1) {
                                if ($request->bill_details[$i][3] != $detailsProduct->price_sale1) {
                                    $detailsProduct->price_sale1 = $request->bill_details[$i][3];
                                    $detailsProduct->save();
                                }
                            } else if ($request->price_type == 2) {
                                if ($request->bill_details[$i][3] != $detailsProduct->price_sale2) {
                                    $detailsProduct->price_sale2 = $request->bill_details[$i][3];
                                    $detailsProduct->save();
                                }
                            } else if ($request->price_type == 3) {
                                if ($request->bill_details[$i][3] != $detailsProduct->price_sale3) {
                                    $detailsProduct->price_sale3 = $request->bill_details[$i][3];
                                    $detailsProduct->save();
                                }
                            } else if ($request->price_type == 4) {
                                if ($request->bill_details[$i][3] != $detailsProduct->price_sale4) {
                                    $detailsProduct->price_sale4 = $request->bill_details[$i][3];
                                    $detailsProduct->save();
                                }
                            }

                        } else {
                            if ($request->bill_details[$i][3] != $detailsProduct->price_sale1) {
                                $detailsProduct->price_sale1 = $request->bill_details[$i][3];
                                $detailsProduct->save();
                            }
                        }
                    } else {//if other unit
                        if (isset($request->price_type)) {
                            if ($request->price_type == 1) {
                                if ($request->bill_details[$i][3] != $detailsProductRelationUnit->price_sale1) {
                                    $detailsProductRelationUnit->price_sale1 = $request->bill_details[$i][3];
                                    $detailsProductRelationUnit->save();
                                }
                            } else if ($request->price_type == 2) {
                                if ($request->bill_details[$i][3] != $detailsProductRelationUnit->price_sale2) {
                                    $detailsProductRelationUnit->price_sale2 = $request->bill_details[$i][3];
                                    $detailsProductRelationUnit->save();
                                }
                            } else if ($request->price_type == 3) {
                                if ($request->bill_details[$i][3] != $detailsProductRelationUnit->price_sale3) {
                                    $detailsProductRelationUnit->price_sale3 = $request->bill_details[$i][3];
                                    $detailsProductRelationUnit->save();
                                }
                            } else if ($request->price_type == 4) {
                                if ($request->bill_details[$i][3] != $detailsProductRelationUnit->price_sale4) {
                                    $detailsProductRelationUnit->price_sale4 = $request->bill_details[$i][3];
                                    $detailsProductRelationUnit->save();
                                }
                            }
                        } else {
                            if ($request->bill_details[$i][3] != $detailsProductRelationUnit->price_sale1) {
                                $detailsProductRelationUnit->price_sale1 = $request->bill_details[$i][3];
                                $detailsProductRelationUnit->save();
                            }
                        }
                    }
                }


                //update stoke if bill type is buy
                $storeId = '';//to store row in store store product =>to used in product_move
                if ($billType == 0 && $detailsProduct->allow_no_qte == false) {
                    //check if this product exit before by this price in stoke
                    $store = Store::where('stoke_id', $bill->stoke_id)->where('product_id', $detailsProduct->id)->where('price', $priceForMainUnit)->where('type', '0')->first();
                    if ($store == '') {//if product not exist in this store
                        $store = new Store();
                        $store->stoke_id = $bill->stoke_id;
                        $store->product_id = $detailsProduct->id;
                        $store->qte = $qteByMainUnit;
                        $store->price = $priceForMainUnit;
                        $store->type = 0;
                        $store->save();
                        $storeId = $store->id;
                    } else {//if product exist in this store
                        $store->qte += $qteByMainUnit;
                        $store->save();
                        $storeId = $store->id;
                    }
                }

                //add bill details (buy and sale)
                $d = new BillDetail();
                $d->bill_id = $bill->id;
                $d->product_id = $detailsProduct->id;
                $d->store_id = ($detailsProduct->allow_no_qte == true || $billType == 1) ? null : $storeId;
                $d->product_unit_id = $detailsUnitId;
                $d->relation_qte = $detailsRelationQte;
                $d->qte = $qteByMainUnit;
                $d->price = $priceForMainUnit;
                $d->save();

                //if bill type is sale
                //check if qte in stoke greater than qte sale , update stoke and add data to sale_make_qte_details
                if ($billType == 1 && $detailsProduct->allow_no_qte == false) {
                    //check if qte in stoke greater than qte sale
                    $productInStore = Store::where('stoke_id', $bill->stoke_id)->where('product_id', $detailsProduct->id)->where('qte', '>', 0)->get();
                    $tempQteResult = $qteByMainUnit;
                    foreach ($productInStore as $p) {
                        if ($tempQteResult > 0) {
                            if ($tempQteResult <= $p->qte) {
                                $p->qte -= $tempQteResult;
                                $p->save();
                                $s = new SaleMakeQteDetails();
                                $s->bill_detail_id = $d->id;
                                $s->store_id = $p->id;
                                $s->qte = $tempQteResult;
                                $s->save();
                                $tempQteResult = 0;
                            } else {
                                $s = new SaleMakeQteDetails();
                                $s->bill_detail_id = $d->id;
                                $s->store_id = $p->id;
                                $s->qte = $p->qte;
                                $s->save();
                                $tempQteResult -= $p->qte;
                                $p->qte = 0;
                                $p->save();
                            }
                        } else {
                            break;
                        }
                    }
                    if ($tempQteResult != 0) {
                        DB::rollback();
                        return 'الكمية ' . ($qteByMainUnit / $detailsRelationQte) . ' ' .
                            ($request->bill_details[$i][1] == 0 ? $detailsProduct->productUnit->name : $detailsProductRelationUnit->productUnit->name) .
                            ' من المنتج ' . $detailsProduct->name . ' غير موجوده فى المخزن بعجز ' . $tempQteResult . $detailsProduct->productUnit->name;
                    }
                }

                //add data to product move (buy and sale)
                $product_move = new ProductMove();
                $product_move->device_id = Auth::user()->device_id;
                $product_move->user_id = Auth::user()->id;
                $product_move->store_id = ($detailsProduct->allow_no_qte == true || $billType == 1) ? null : $storeId;
                $product_move->stoke_id = $detailsProduct->allow_no_qte == true ? null : $bill->stoke_id;
                $product_move->product_id = $detailsProduct->id;
                $product_move->product_unit_id = $detailsUnitId;
                $product_move->relation_qte = $detailsRelationQte;
                $product_move->qte = $qteByMainUnit;
                $product_move->price = $priceForMainUnit;
                $product_move->bill_id = $bill->id;
                $product_move->type = $billType;
                $product_move->note = $bill->message;
                $product_move->save();

            }


            //add account_calculation
            if ($bill->account_id != null) {
                $ac = new AccountCalculation();
                $ac->device_id = Auth::user()->device_id;
                $ac->user_id = Auth::user()->id;
                $ac->account_id = $bill->account_id;
                $ac->value = ($bill->total_price);
                $ac->rent = ($bill->total_price - $bill->total_paid);
                $ac->type = ($bill->type == 0 ? 4 : 5);
                $ac->bill_id = $bill->id;
                $ac->account_after_this_action = $bill->account->account;
                $ac->note = $bill->message;
                if ($bill->total_price != ($bill->total_paid - $bill->discount)) {
                    if ($bill->type == 1 && $account->is_supplier) {//bill sale for supplier
                        $ac->relation_account = 2;
                    } else {
                        $ac->relation_account = 1;
                    }
                }
                $ac->save();
            }


            $activity_bill->data = 'إضافة فاتورة ' . ($billType == 0 ? 'شراء ' : 'بيع ') . ($account == '' ? 'بدون شخص ' : ('ل' . $account->name)) .
                ' بخصم ' . $bill->discount . 'ج' . ' وإجمالى بعد الخصم ' . $bill->total_price . 'ج' . ' والمبلغ المدفوع ' . $bill->total_paid . 'ج' . ' ورقم الفاتورة ' . $bill->id .
                ' ليصبح المال فى الدرج ' . round($device->treasury_value, 2) . 'ج';
            $activity_bill->type = $billType == 0 ? 13 : 14;
            if ($bill->total_paid > 0) {
                if ($bill->type == 0) {
                    $activity_bill->relation_treasury = 2;
                    $activity_bill->treasury_value = $bill->total_paid;
                } else {
                    $activity_bill->relation_treasury = 1;
                    $activity_bill->treasury_value = $bill->total_paid;
                }
            }
            $activity_bill->save();

            Session::flash('success', 'إضافة فاتورة ' . ($billType == 0 ? 'شراء ' : 'بيع '));
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
//            Session::flash('fault',$e->getMessage());
//            return back();
//            throw $e;
        }
        DB::commit();
        return 'success';

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Bill $bill
     * @return \Illuminate\Http\Response
     */
    public function show(Bill $bill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Bill $bill
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $bill = Bill::with('details')->with('Stoke')->findOrFail($id);
        $products = ($bill->type == 0) ? (Product::where('allow_buy', 1)->where('state', 1)->orderby('name')->get()) : (Product::where('allow_sale', 1)->where('state', 1)->orderby('name')->get());

        return view('bills.edit', [
            'products' => $products,
            'bill_messages' => BillMessage::where('state', 1)->orderby('name')->get(),
            'bill' => $bill,
            'setting' => Setting::first(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Bill $bill
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        //
        $bill = Bill::with('device')->with('account')->with('accountCalculation')->with('detail')->findOrFail($id);
        $billType = $bill->type;
        $s = Setting::first();
        $device = Device::where('id', Auth::user()->device_id)->first();


        //check power
        $checkPower = ($bill->type == 0 ? Auth::user()->allow_edit_bill_buy : Auth::user()->allow_edit_bill_sale);
        $checkPower = Auth::user()->type == 1 ? true : $checkPower;
        if ($checkPower == false) {
            $activty = new Activity();
            $activty->data = 'قام المستخدم ' . Auth::user()->name . ' بمحاولة ' .
                ($bill->type == 0 ? 'تعديل فاتورة شراء' : 'تعديل فاتورة بيع') . 'غير مصرح له بتعديلها حيث رقم الفاتورة ' . $bill->id .
                (Auth::user()->log_out_security ? 'وتم عمل تسجيل خروج له' : '');

            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->type = 0;
            $activty->notification = 1;
            $activty->save();

            if (Auth::user()->log_out_security) {
                $request->session()->invalidate();
                return redirect(\route('login'));
            } else {
                Session::flash('fault', 'غير مصرح لك بفتح هذة الصفحة !');
                return redirect(route('home'));
            }
        }
        //end check power

        //validate if account in no account (account_id=0)
        if ($bill->account_id == null) {
            if (round($request->total_price) != round($request->total_paid)) {
                return 'حصل خطاء فى العملية يجب أن يتم دفع المبلغ بالكامل إذا كانت الفاتورة بدون شخص';
            }
        }

        $validator = '';
        $validator = Validator::make($request->all(), [
            'bill_details.*.0' => 'required|exists:products,id',//product_id
            'bill_details.*.1' => 'required|numeric',//unit_id ,0 for main unit
            'bill_details.*.2' => ['gt:0', new valid_qte],// qte
            'bill_details.*.3' => ['gt:-1', new valid_price],//price

            'discount' => ['gt:-1', new valid_price],
            'total_price' => ['gt:0', new valid_price],//total price after discount
            'total_paid' => ['gt:-1', new valid_price],
            'message' => 'max:150',
        ]);
        if (count($validator->errors()) > 0) {
            $error = 'حصل خصاء فى العملية ';

            if (isset($validator->errors()->get('*')['total_price'])) {
                $error .= '<br/>';
                $error .= 'خطاء فى إجمالى الفاتورة ';
            }
            if (isset($validator->errors()->get('*')['discount'])) {
                $error .= '<br/>';
                $error .= 'خطاء فى الخصم ';
            }
            if (isset($validator->errors()->get('*')['total_paid'])) {
                $error .= '<br/>';
                $error .= 'خطاء فى المبلغ المدفوع ';
            }
            if (isset($validator->errors()->get('*')['message'])) {
                $error .= '<br/>';
                $error .= 'خطاء فى رسالة الفاتورة ';
            }
            if ($error == 'حصل خصاء فى العملية ') {
                $error .= 'خطاء فى المنتجات فى الفاتورة';
            }
            return $error;
        }


        DB::beginTransaction();
        try {
            $activity_bill = new Activity();
            $activity_bill->user_id = Auth::user()->id;
            $activity_bill->device_id = Auth::user()->device_id;


            //delete details in store
            //update store  (bill buy)
            if ($bill->type == 0) {
                //update store and delete details
                foreach ($bill->detail as $d) {
                    //check if qte in store not less than qte delete
                    if ($d->store != '') {
                        if ($d->store->qte < $d->qte) {
                            DB::rollback();
                            return 'حصل خطاء فى العملية , الكمية من المنتج ' . $d->product->name . ' التى تم شرائها بسعر ' .
                                $d->price . '  لكل ' . $d->productUnit->name . ' غير كافية فى المخزن لتعديل الفاتورة بعجز ' .
                                (($d->qte - $d->store->qte) / $d->relation_qte) . ' ' . $d->productUnit->name;
                        } else {
                            $d->store->qte -= $d->qte;
                            $d->store->save();
                        }
                    }
                }
            }
            //update store (bill sale)
            if ($bill->type == 1) {
                foreach ($bill->detail as $d) {
                    //add qte to qte in store
                    if ($d->saleMakeQteDetail != '') {
                        foreach ($d->saleMakeQteDetail as $smqd) {
                            $smqd->store->qte += $smqd->qte;
                            $smqd->store->save();
                        }
                    }
                }
            }
            //end delete details in store
            //delete old details
            BillDetail::where('bill_id', $bill->id)->delete();
            $oldProducMove = ProductMove::where('bill_id', $bill->id)->get();
            foreach ($oldProducMove as $old) {
                if ($bill->type == 0) {
                    $old->type = 12;
                } else {
                    $old->type = 13;
                }
                $old->save();
            }

            //add details for bill and update stoke
            for ($i = 0; $i < count($request->bill_details); $i++) {
                $detailsProduct = Product::find($request->bill_details[$i][0]);
                $detailsProductRelationUnit = ($request->bill_details[$i][1] == 0 ? null : RelationProductUnit::find($request->bill_details[$i][1]));
                $detailsUnitId = ($request->bill_details[$i][1] == 0 ? $detailsProduct->product_unit_id : $detailsProductRelationUnit->product_unit_id);
                $detailsRelationQte = ($request->bill_details[$i][1] == 0 ? 1 : $detailsProductRelationUnit->relation_qte);
                $qteByMainUnit = $request->bill_details[$i][2] * $detailsRelationQte;
                $priceForMainUnit = $request->bill_details[$i][3] / $detailsRelationQte;


                //update product price if price change and setting is edit price automatic (buy and sale)
                if ($billType == 0 && $s->auto_update_price_product_bill_buy) {//(for bill buy)
                    if ($request->bill_details[$i][1] == 0) {//if product qte is main qte
                        if ($request->bill_details[$i][3] != $detailsProduct->price_buy) {
                            $detailsProduct->price_buy = $request->bill_details[$i][3];
                            $detailsProduct->save();
                        }
                    } else {//if other unit
                        if ($request->bill_details[$i][3] != $detailsProductRelationUnit->price_buy) {
                            $detailsProductRelationUnit->price_buy = $request->bill_details[$i][3];
                            $detailsProductRelationUnit->save();
                        }
                    }
                }
                if ($billType == 1 && $s->auto_update_price_product_bill_sale) {//(for bill sale)
                    if ($request->bill_details[$i][1] == 0) {//if product qte is main qte
                        if ($request->bill_details[$i][3] != $detailsProduct->price_buy) {
                            $detailsProduct->price_sale1 = $request->bill_details[$i][3];
                            $detailsProduct->save();
                        }
                    } else {//if other unit
                        if ($request->bill_details[$i][3] != $detailsProductRelationUnit->price_buy) {
                            $detailsProductRelationUnit->price_sale1 = $request->bill_details[$i][3];
                            $detailsProductRelationUnit->save();
                        }
                    }
                }


                //update stoke if bill type is buy
                $storeId = '';//to store row in store store product =>to used in product_move
                if ($billType == 0 && $detailsProduct->allow_no_qte == false) {
                    //check if this product exit before by this price in stoke
                    $store = Store::where('stoke_id', $bill->stoke_id)->where('product_id', $detailsProduct->id)->where('price', $priceForMainUnit)->where('type', '0')->first();
                    if ($store == '') {//if product not exist in this store
                        $store = new Store();
                        $store->stoke_id = $bill->stoke_id;
                        $store->product_id = $detailsProduct->id;
                        $store->qte = $qteByMainUnit;
                        $store->price = $priceForMainUnit;
                        $store->type = 0;
                        $store->save();
                        $storeId = $store->id;
                    } else {//if product exist in this store
                        $store->qte += $qteByMainUnit;
                        $store->save();
                        $storeId = $store->id;
                    }
                }

                //add bill details (buy and sale)
                $d = new BillDetail();
                $d->bill_id = $bill->id;
                $d->product_id = $detailsProduct->id;
                $d->store_id = ($detailsProduct->allow_no_qte == true || $billType == 1) ? null : $storeId;
                $d->product_unit_id = $detailsUnitId;
                $d->relation_qte = $detailsRelationQte;
                $d->qte = $qteByMainUnit;
                $d->price = $priceForMainUnit;
                $d->save();

                //if bill type is sale
                //check if qte in stoke greater than qte sale , update stoke and add data to sale_make_qte_details
                if ($billType == 1 && $detailsProduct->allow_no_qte == false) {
                    //check if qte in stoke greater than qte sale
                    $productInStore = Store::where('stoke_id', $bill->stoke_id)->where('product_id', $detailsProduct->id)->where('qte', '>', 0)->get();
                    $tempQteResult = $qteByMainUnit;
                    foreach ($productInStore as $p) {
                        if ($tempQteResult > 0) {
                            if ($tempQteResult <= $p->qte) {
                                $p->qte -= $tempQteResult;
                                $p->save();
                                $s = new SaleMakeQteDetails();
                                $s->bill_detail_id = $d->id;
                                $s->store_id = $p->id;
                                $s->qte = $tempQteResult;
                                $s->save();
                                $tempQteResult = 0;
                            } else {
                                $s = new SaleMakeQteDetails();
                                $s->bill_detail_id = $d->id;
                                $s->store_id = $p->id;
                                $s->qte = $p->qte;
                                $s->save();
                                $tempQteResult -= $p->qte;
                                $p->qte = 0;
                                $p->save();
                            }
                        } else {
                            break;
                        }
                    }
                    if ($tempQteResult != 0) {
                        DB::rollback();
                        return 'الكمية ' . ($qteByMainUnit / $detailsRelationQte) . ' ' .
                            ($request->bill_details[$i][1] == 0 ? $detailsProduct->productUnit->name : $detailsProductRelationUnit->productUnit->name) .
                            ' من المنتج ' . $detailsProduct->name . ' غير كافية للتعديل فى المخزن بعجز ' . ($tempQteResult / $detailsRelationQte) . $detailsProduct->productUnit->name;
                    }
                }

                //add data to product move (buy and sale)
                $product_move = new ProductMove();
                $product_move->device_id = Auth::user()->device_id;
                $product_move->user_id = Auth::user()->id;
                $product_move->store_id = ($detailsProduct->allow_no_qte == true || $billType == 1) ? null : $storeId;
                $product_move->stoke_id = $detailsProduct->allow_no_qte == true ? null : $bill->stoke_id;
                $product_move->product_id = $detailsProduct->id;
                $product_move->product_unit_id = $detailsUnitId;
                $product_move->relation_qte = $detailsRelationQte;
                $product_move->qte = $qteByMainUnit;
                $product_move->price = $priceForMainUnit;
                $product_move->bill_id = $bill->id;
                $product_move->type = $billType == 0 ? 14 : 15;
                $product_move->note = isset($request->message) ? $request->message : ' ';;
                $product_move->save();
            }

            $oldTotalPrice = $bill->total_price;
            $oldTotalPaid = $bill->total_paid;
            $oldDiscount = $bill->discount;
            $oldMessage = $bill->message;
            $oldRent = $oldTotalPrice - $oldTotalPaid;

            //update bill
            $bill->total_price = $request->total_price;
            $bill->total_paid = $request->total_paid;
            $bill->discount = $request->discount;
            $bill->message = isset($request->message) ? $request->message : ' ';
            //update treasury and account and add account calculation
            $account = '';
            if (isset($bill->account)) {
                $account = $bill->account;
                $account->device_id = Auth::user()->device_id;
            }
            if ($bill->type == 0) {
                //update treasury
                $device->treasury_value -= ($request->total_paid - $oldTotalPaid);
                $activity_bill->relation_treasury = ($oldTotalPaid - $request->total_paid) > 0 ? 2 : 1;
                $activity_bill->treasury_value = abs($oldTotalPaid - $request->total_paid);
                $device->save();
                //update account
                if (isset($account->account)) {
                    $account->account += ($request->total_price - $request->total_paid) - $oldRent;
                    $account->save();
                }
            } else {
                $device->treasury_value += ($request->total_paid - $oldTotalPaid);
                $activity_bill->relation_treasury = ($request->total_paid - $oldTotalPaid) > 0 ? 1 : 2;
                $activity_bill->treasury_value = ($request->total_paid - $oldTotalPaid);
                $device->save();

                //update account
                if (isset($account->account)) {
                    if ($account->is_supplier) {//bill sale for supplier
                        $account->account -= ($request->total_price - $request->total_paid) - $oldRent;
                    } else {
                        $account->account += ($request->total_price - $request->total_paid) - $oldRent;
                    }
                    $account->save();
                }
            }

            $bill->save();

            //add account_calculation
            if ($bill->account_id != null) {
                $ac = new AccountCalculation();
                $ac->device_id = Auth::user()->device_id;
                $ac->user_id = Auth::user()->id;
                $ac->account_id = $bill->account_id;
                $ac->value = ($bill->total_price);
                $ac->rent = ($bill->total_price - $bill->total_paid) - $oldRent;
                $ac->type = ($bill->type == 0 ? 4 : 5);
                $ac->bill_id = $bill->id;
                $ac->account_after_this_action = $bill->account->account;
                $ac->note = 'تعديل الفاتورة, ' . $bill->message;
                if ($bill->total_price != ($bill->total_paid - $bill->discount)) {
                    if ($bill->type == 1 && $account->is_supplier) {//bill sale for supplier
                        $ac->relation_account = 2;
                    } else {
                        $ac->relation_account = 1;
                    }
                }
                $ac->save();
            }

            $activityMessageBill = '';
            if ($oldMessage != '' || $bill->message != '') {
                $activityMessageBill = ' وكانت رسالة الفاتورة قبل التعديل هى ' . $oldMessage . ' وبعد التعديل ' . $bill->message;
            }
            $activity_bill->data = 'تعديل فاتورة ' . ($billType == 0 ? 'شراء رقم ' : 'بيع رقم ') . $bill->id . ($account == '' ? 'بدون شخص ' : ('بإسم ' . $account->name)) .
                ' بخصم ' . $bill->discount . 'ج' . ' وإجمالى بعد الخصم ' . $bill->total_price . 'ج' . ' والمبلغ المدفوع ' . $bill->total_paid . 'ج' .
                ' ليصبح المال فى الدرج ' . round($device->treasury_value, 2) . 'ج' . $activityMessageBill;
            $activity_bill->type = $billType == 0 ? 13 : 14;
            if ($bill->total_paid > 0) {
                if ($bill->type == 0) {
                    $activity_bill->relation_treasury = 2;
                    $activity_bill->treasury_value = ($oldTotalPaid - $request->total_paid);
                } else {
                    $activity_bill->relation_treasury = 1;
                    $activity_bill->treasury_value = ($request->total_paid - $oldTotalPaid);
                }
            }
            $activity_bill->save();

            Session::flash('success', 'تعديل فاتورة ' . ($billType == 0 ? 'شراء ' : 'بيع '));
        } catch (\Exception $e) {
            DB::rollback();
//            return $e->getMessage();
//            Session::flash('fault',$e->getMessage());
//            return back();
            throw $e;
        }
        DB::commit();
        return 'success';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Bill $bill
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $r)
    {

        $bill = Bill::with('device')->with('account')->with('accountCalculation')->with('detail')->findOrFail($id);
//        return $bill;
        //check power
        $checkPower = ($bill->type == 0 ? Auth::user()->allow_delete_bill_buy : Auth::user()->allow_delete_bill_sale);
        $checkPower = Auth::user()->type == 1 ? true : $checkPower;
        if ($checkPower == false) {
            $activty = new Activity();
            $activty->data = 'قام المستخدم ' . Auth::user()->name . ' بمحاولة ' .
                ($bill->type == 0 ? 'حذف فاتورة شراء' : 'حذف فاتورة بيع') . 'غير مصرح له بحذفها حيث رقم الفاتورة ' . $bill->id .
                (Auth::user()->log_out_security ? 'وتم عمل تسجيل خروج له' : '');

            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->type = 0;
            $activty->notification = 1;
            $activty->save();

            if (Auth::user()->log_out_security) {
                $r->session()->invalidate();
                return redirect(\route('login'));
            } else {
                Session::flash('fault', 'غير مصرح لك بفتح هذة الصفحة !');
                return redirect(route('home'));
            }
        }
        //end check power

        //delete bill
        DB::beginTransaction();
        try {
            //update store  (bill buy)
            if ($bill->type == 0) {
                //update store and delete details
                foreach ($bill->detail as $d) {
                    //check if qte in store not less than qte delete
                    if ($d->store != '') {
                        if ($d->store->qte < $d->qte) {
                            DB::rollback();
                            Session::flash('fault', 'حصل خطاء فى العملية , الكمية من المنتج ' . $d->product->name . ' التى تم شرائها بسعر ' . $d->price . '  لكل ' . $d->productUnit->name . ' غير كافية لحذف الفاتورة بعجز ' . ($d->qte - $d->store->qte) . ' ' . $d->productUnit->name);
                            return back();
                        } else {
                            $d->store->qte -= $d->qte;
                            $d->store->save();
                        }
                    }
                }
            }

            //update store (bill sale)
            if ($bill->type == 1) {
                foreach ($bill->detail as $d) {
                    //add qte to qte in store
                    if ($d->saleMakeQteDetail != '') {
                        foreach ($d->saleMakeQteDetail as $smqd) {
                            $smqd->store->qte += $smqd->qte;
                            $smqd->store->save();
                        }
                    }
                }
            }

            //update account and account calculation in bill has account (buy or sale)
            if ($bill->account != null) {
                if ($bill->type == 1 && $bill->account->is_supplier) {//for bill sale for suppier
                    //update account
                    $bill->account->account += ($bill->total_price - $bill->total_paid);
                    $bill->account->save();

                    //update account calculations
                    $nextAccountCalclution = AccountCalculation::where('id', '>', $bill->accountCalculation->id)->get();
                    foreach ($nextAccountCalclution as $x) {
                        $x->account_after_this_action += $bill->accountCalculation->rent;
                        $x->save();
                    }
                } else {
                    //update account
                    $bill->account->account -= ($bill->total_price - $bill->total_paid);
                    $bill->account->save();

                    //update account calculations
                    $nextAccountCalclution = AccountCalculation::where('id', '>', $bill->accountCalculation->id)->get();
                    foreach ($nextAccountCalclution as $x) {
                        $x->account_after_this_action -= $bill->accountCalculation->rent;
                        $x->save();
                    }
                }
            }

            //update treasure (buy or sale)
            if ($bill->total_paid != 0) {
                if ($bill->type == 0) {//bill buy
                    $bill->device->treasury_value += $bill->total_paid;
                } else {//bill sale
                    $bill->device->treasury_value -= $bill->total_paid;
                }
                $bill->device->save();
            }

            //add activity (buy or sale)
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;

            if ($bill->total_paid != 0) {
                $activity->relation_treasury = ($bill->type == 0) ? 1 : 2;
                $activity->treasury_value = $bill->total_paid;
            }

            $activity->data = 'حذف فاتورة ' . ($bill->type == 0 ? ' شراء برقم ' : ' بيع برقم ') . $bill->id .
                ' لمورد بإسم ' . ($bill->account_id == null ? 'بدون مورد' : ($bill->account->name . ' ليصبح حسابة ' . round($bill->account->account, 2) . 'ج')) . ' ليصبح المال فى الدرج ' . $bill->device->treasury_value . 'ج ' . ' وكانت الفاتورة برسالة ' . $bill->message;
            $activity->type = ($bill->type == 0 ? 13 : 14);
            if (Auth::user()->type != 1 &&
                Auth::user()->notification_delete_bill_buy && $bill->type == 0) {
                $activity->notification = 1;
            }
            if (Auth::user()->type != 1 &&
                Auth::user()->notification_delete_bill_sale && $bill->type == 1) {
                $activity->notification = 1;
            }
            $activity->save();

            //delete bill
            $bill->delete();
            Session::flash('success', 'تمت العملية بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
//            Session::flash('fault',$e->getMessage());
//            return back();
//            throw $e;
        }
        DB::commit();
        return back();

    }

    public function getData(Request $r)
    {
        if ($r->type == 'getDataByDateCreate') {
            //get by date create for one account
            if ($r->account_id != '' && $r->account_id != '0') {
                if ($r->stateBill == '') {
                    return Bill::with('visit')->with('billBack')->with('user')->with('device')->with('account')->with('stoke')->
                    orderby('id', 'desc')->where('account_id', $r->account_id)->
                    whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
                } else {
                    return Bill::with('visit')->with('billBack')->with('user')->with('device')->with('account')->with('stoke')->
                    orderby('id', 'desc')->
                    where('type', $r->stateBill)->where('account_id', $r->account_id)->
                    whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
                }
            }

            //get by date create for all account
            if ($r->account_id == '') {
                if ($r->stateBill == '') {
                    return Bill::with('visit')->with('billBack')->with('user')->with('device')->with('account')->with('stoke')->
                    orderby('id', 'desc')->
                    whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
                } else {
                    return Bill::with('visit')->with('billBack')->with('user')->with('device')->with('account')->with('stoke')->
                    orderby('id', 'desc')->
                    where('type', $r->stateBill)->
                    whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
                }
            }

            //get by date create for no account
            if ($r->account_id == '0') {
                if ($r->stateBill == '') {
                    return Bill::with('billBack')->with('user')->with('device')->with('account')->with('stoke')->
                    orderby('id', 'desc')->whereNull('account_id')->
                    whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
                } else {
                    return Bill::with('billBack')->with('user')->with('device')->with('account')->with('stoke')->
                    orderby('id', 'desc')->
                    where('type', $r->stateBill)->whereNull('account_id')->
                    whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
                }
            }

        }
        if ($r->type == 'getDataByBillId') {
            if ($r->stateBill == '') {
                return Bill::with('visit')->with('billBack')->with('user')->with('device')->with('account')->with('stoke')->
                orderby('id', 'desc')->
                where('id', $r->id)->get();
            } else {
                return Bill::with('visit')->with('billBack')->with('user')->with('device')->with('account')->with('stoke')->
                orderby('id', 'desc')->
                where('type', $r->stateBill)->
                where('id', $r->id)->get();
            }
        }

        if ($r->type == 'getBillDetails') {
            if ($r->show_profit == 'true') {//to show details with profit if bill type is sale
                return BillDetail::with('bill')->with('product')->with('saleMakeQteDetail')->with('productUnit')->
                where('bill_id', $r->id)->get();
            } else {
                return BillDetail::with('bill')->with('product')->with('productUnit')->
                where('bill_id', $r->id)->get();
            }

        }

        if ($r->type == 'getBillBack') {
            return BillBack::with('user')->with('device')->where('bill_id', $r->id)->get();
        }

        if ($r->type == 'getBillBackDetails') {
            return BillBackDetail::with('productUnit')->with('Product')->where('bill_back_id', $r->id)->get();
        }

        if ($r->type = 'getAccountBillByDateCreate') {
            return Bill::with('details')->where('account_id', $r->account_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->orderBy('id', 'desc')->get();
        }

    }

    public function print($id = '', Request $request)
    {
        $print_dismissal_notice = data_get($request, 'dismissal_notice');
        $device = Auth::user()->device;
        $setting = Setting::first();

        if ($id == '' || $id == 0) {
            $bill = Bill::with('account')->with('details')->where('device_id', $device->id)->latest()->first();
        } else {
            $bill = Bill::with('account')->with('details')->where('device_id', $device->id)->findOrFail($id);
        }
        return view('bills.print_bill', [
            'bill' => $bill,
            'design' => BillPrint::where('id', ($device->design_bill_print ? $device->design_bill_print : 1))->first(),
            'setting' => $setting,
            'print_dismissal_notice'=>$print_dismissal_notice,
            'new_page' => '<div style="page-break-after:always"><span style="display:none">&nbsp;</span></div>'
        ]);
    }

    public function create_bill_back($id, $type = 0)//type 0 for bill buy,1 for bill sale
    {
        return view('bills.bill_back_create', [
            'bill' => Bill::with('account')->with('details')->findOrFail($id),
            'type' => $type,
            'setting' => Setting::first(),
        ]);
    }

    public function store_bill_back($bill_id, Request $r)
    {
        $bill = Bill::with('account')->with('details')->findOrFail($bill_id);
        DB::beginTransaction();
        try {
            //add new bill back
            $back = new BillBack();
            $back->user_id = Auth::user()->id;
            $back->device_id = Auth::user()->device_id;
            $back->bill_id = $bill_id;
            $back->type = $r->bill_back_type;//0 =>replace , 1=> take money,2 => discount from account
            $back->note = isset($r->note) ? $r->note : '';
            $back->total_price = $r->total_price;
            $back->save();


            //add back details and update store
            for ($i = 0; $i < count($r->details_id); $i++) {
                if ($r->qte_back[$i] == 0) {
                    continue;
                }

                //get bill details
                $details = BillDetail::with('store')->with('saleMakeQteDetail')->find($r->details_id[$i]);

                //update bill details if type not replace
                if ($back->type != 0) {
                    $details->qte -= $r->qte_back[$i] * $details->relation_qte;
                    $details->save();
                }


                //add bill back details
                $backDetails = new BillBackDetail();
                $backDetails->bill_back_id = $back->id;
                $backDetails->bill_details_id = $r->details_id[$i];
                $backDetails->product_id = $details->product_id;
                $backDetails->product_unit_id = $details->product_unit_id;
                $backDetails->relation_qte = $details->relation_qte;
                $backDetails->qte = $r->qte_back[$i] * $details->relation_qte;
                $backDetails->price = $details->price;
                $backDetails->store_id = isset($details->store_id) ? $details->store_id : null;
                $backDetails->save();

                //update store if bill type is buy and type back not replace
                if ($backDetails->store_id != null && $back->type != 0) {
                    //check if qte in store less than qte in back
                    if ($details->store->qte < $backDetails->qte) {
                        DB::rollBack();
                        Session::flash('fault', 'حصل خطاء فى العملية , الكمية من المنتج ' . $details->product->name . ' المراد إرجاعها غير موجودة فى المخزن حيث الكمية الموجودة هى ' .
                            $details->store->qte . ' ' . $details->product->productUnit->name);
                        return back();
                    }
                    $details->store->qte -= $backDetails->qte;
                    $details->store->save();

                    //add product move
                    $pm = new ProductMove();
                    $pm->device_id = Auth::user()->device_id;
                    $pm->user_id = Auth::user()->id;
                    $pm->store_id = $details->store->id;
                    $pm->stoke_id = $details->store->stoke_id;
                    $pm->product_id = $details->store->product_id;
                    $pm->product_unit_id = $details->product_unit_id;
                    $pm->relation_qte = $details->relation_qte;
                    $pm->qte = $backDetails->qte;
                    $pm->price = $backDetails->price;
                    $pm->type = 6;
                    $pm->bill_id = $bill_id;
                    $pm->bill_back_id = $back->id;
                    $pm->note = $back->note;
                    $pm->save();
                }
                //update store if bill type is sale and type back not replace
                if ($backDetails->store_id == null && $back->type != 0) {
                    $tempQte = $backDetails->qte;
                    for ($j = 0; $j < count($details['saleMakeQteDetail']); $j++) {
                        if ($tempQte == 0) {
                            break;
                        }
                        if ($tempQte < $details['saleMakeQteDetail'][$j]['qte']) {
                            //subtract qte from saleMakeQteDetail
                            $details['saleMakeQteDetail'][$j]['qte'] -= $tempQte;
                            $details['saleMakeQteDetail'][$j]->save();

                            //add qte to store
                            $backStore = Store::find($details['saleMakeQteDetail'][$j]->store_id);
                            $backStore->qte += $tempQte;
                            $backStore->save();

                            //add product move
                            $pm = new ProductMove();
                            $pm->device_id = Auth::user()->device_id;
                            $pm->user_id = Auth::user()->id;
                            $pm->store_id = $backStore->id;
                            $pm->stoke_id = $backStore->stoke_id;
                            $pm->product_id = $backStore->product_id;
                            $pm->product_unit_id = $details->product_unit_id;
                            $pm->relation_qte = $details->relation_qte;
                            $pm->qte = $tempQte;
                            $pm->price = $backDetails->price;
                            $pm->type = 7;
                            $pm->bill_id = $bill_id;
                            $pm->bill_back_id = $back->id;
                            $pm->note = $back->note;
                            $pm->save();

                            $tempQte = 0;
                        } else {
                            //add qte to store
                            $backStore = Store::find($details['saleMakeQteDetail'][$j]->store_id);
                            $backStore->qte += $details['saleMakeQteDetail'][$j]['qte'];
                            $backStore->save();

                            //add product move
                            $pm = new ProductMove();
                            $pm->device_id = Auth::user()->device_id;
                            $pm->user_id = Auth::user()->id;
                            $pm->store_id = $backStore->id;
                            $pm->stoke_id = $backStore->stoke_id;
                            $pm->product_id = $backStore->product_id;
                            $pm->product_unit_id = $details->product_unit_id;
                            $pm->relation_qte = $details->relation_qte;
                            $pm->qte = $details['saleMakeQteDetail'][$j]['qte'];
                            $pm->price = $backDetails->price;
                            $pm->type = $bill->type == 0 ? 6 : 7;
                            $pm->bill_id = $bill_id;
                            $pm->bill_back_id = $back->id;
                            $pm->note = $back->note;
                            $pm->save();


                            $tempQte -= $details['saleMakeQteDetail'][$j]['qte'];

                            //subtract qte from saleMakeQteDetail
                            $details['saleMakeQteDetail'][$j]['qte'] = 0;
                            $details['saleMakeQteDetail'][$j]->save();
                        }
                    }
                }


                //add product move if bill typ is replace
                if ($back->type == 0) {
                    //add product move
                    $pm = new ProductMove();
                    $pm->device_id = Auth::user()->device_id;
                    $pm->user_id = Auth::user()->id;
                    $pm->stoke_id = $details->bill->stoke_id;
                    $pm->product_id = $details->product_id;
                    $pm->product_unit_id = $details->product_unit_id;
                    $pm->relation_qte = $details->relation_qte;
                    $pm->qte = $backDetails->qte;
                    $pm->price = $backDetails->price;
                    $pm->type = $backDetails->store_id != null ? 8 : 9;
                    $pm->bill_id = $bill_id;
                    $pm->bill_back_id = $back->id;
                    $pm->note = $back->note;
                    $pm->save();
                }
            }

            //update account if back type not replace and update treasure and update total bill in bill ,buy and sale
            if ($r->bill_back_type != 0) {
                if ($bill->account_id == null) {//if no account back type is take Money, update treasure
                    $activity = new Activity();
                    $device = Device::find(Auth::user()->device_id);
                    if ($bill->type == 0) {
                        $device->treasury_value += $r->total_price;
                        $device->save();

                        $activity->relation_treasury = 1;
                        $activity->treasury_value = $r->total_price;
                        $activity->data = 'إضافة مرتجع نوعة أخذ مال لفاتورة شراء رقم ' . $bill->id . ' بإجمالى ' . $back->total_price . 'ج' . ' وتم إضافة المبلغ إلى الدرج ليصبح المال فى الدرج  ' . round($device->treasury_value, 2) . 'ج';

                    } else {
                        if ($device->treasury_value < $r->total_price) {
                            DB::rollBack();;
                            Session::flash('fault', 'حصل خطاء فى العملية المال فى الدرج غير كافى لإرجاع المال حيث المال فى الدرج ' . round($device['treasury_value'], 2) . ' ج ' . ' والمبلغ المراد إرجاعة ' .
                                round($r->total_price, 2) . ' ج');
                            return back();
                        }
                        $device->treasury_value -= $r->total_price;
                        $device->save();

                        $activity->relation_treasury = 2;
                        $activity->treasury_value = $r->total_price;
                        $activity->data = 'إضافة مرتجع نوعة أخذ مال لفاتورة بيع رقم ' . $bill->id . ' بإجمالى ' . $back->total_price . 'ج' . ' وتم خصم المبلغ من الدرج ';
                    }

                    //update total in bill
                    $bill->total_price -= $r->total_price;
                    $bill->total_paid -= $r->total_price;
                    $bill->save();

                    $activity->user_id = Auth::user()->id;
                    $activity->device_id = Auth::user()->device_id;
                    $activity->type = $bill->type == 0 ? 13 : 14;
                    $activity->save();
                } else {
                    //update total in bill
                    $bill->total_price -= $r->total_price;
                    $bill->save();

                    //type take money or discount from account
                    if ($back->type == 1) {
                        $activity = new Activity();

                        //if bill type is bill buy
                        if ($bill->type == 0) {
                            $activity->data = 'إضافة مرتجع نوعة أخذ مال لفاتورة شراء رقم ' . $bill->id . ' بإجمالى ' . $back->total_price . 'ج' . ' وتم إضافة المبلغ إلى الدرج ';
                            $activity->relation_treasury = 1;
                            $activity->treasury_value = $r->total_price;

                            $device = Device::find(Auth::user()->device_id);
                            $device->treasury_value += $r->total_price;
                            $device->save();
                        } else {// bill type is bill sale
                            $activity->data = 'إضافة مرتجع نوعة أخذ مال لفاتورة بيع رقم ' . $bill->id . ' بإجمالى ' . $back->total_price . 'ج' . ' وتم خصم المبلغ من الدرج ';
                            $device = Device::find(Auth::user()->device_id);
                            if ($device->treasury_value < $r->total_price) {
                                DB::rollBack();;
                                Session::flash('fault', 'حصل خطاء فى العملية المال فى الدرج غير كافى لإرجاع المال حيث المال فى الدرج ' . round($device['treasury_value'], 2) . ' ج ' . ' والمبلغ المراد إرجاعة ' .
                                    round($r->total_price, 2) . ' ج');
                                return back();
                            }
                            $device->treasury_value -= $r->total_price;
                            $device->save();

                            $activity->relation_treasury = 2;
                            $activity->treasury_value = $r->total_price;
                        }

                        $activity->user_id = Auth::user()->id;
                        $activity->device_id = Auth::user()->device_id;
                        $activity->type = $bill->type == 0 ? 13 : 14;
                        $activity->save();
                    } else if ($back->type == 2) {
                        $activity = new Activity();
                        //if account is supplier customer
                        if ($bill->account->is_supplier && $bill->account->is_customer) {
                            if ($bill->type == 0) {
                                $bill->account->account -= $r->total_price;
                                $bill->account->save();
                                $activity->data = 'إضافة مرتجع نوعة خصم من الحساب لفاتورة شراء رقم ' . $bill->id . ' بإجمالى ' . $back->total_price . 'ج' . ' وتم خصم المبلغ من حساب المورد العميل ' . $bill->account->name . ' ليصبح حسابة ' . round($bill->account->account, 2) . 'ج';
                            } else {
                                $bill->account->account -= $r->total_price;
                                $bill->account->save();
                                $activity->data = 'إضافة مرتجع نوعة خصم من الحساب لفاتورة بيع رقم ' . $bill->id . ' بإجمالى ' . $back->total_price . 'ج' . ' وتم خصم المبلغ من حساب المورد العميل ' . $bill->account->name . ' ليصبح حسابة ' . round($bill->account->account, 2) . 'ج';
                            }
                        } else {
                            $bill->account->account -= $r->total_price;
                            $bill->account->save();
                            $activity->data = "إضافة مرتجع نوعة خصم من الحساب لفاتورة " . ($bill->type == 0 ? ' شراء ' : ' بيع ') .
                                " رقم " . $bill->id . ' بإجمالى ' . $back->total_price . 'ج' . ' وتم خصم المبلغ من حساب ' . ($bill->type == 0 ? ' المورد ' : ' العميل ') . $bill->account->name;
                        }


                        $activity->user_id = Auth::user()->id;
                        $activity->device_id = Auth::user()->device_id;
                        $activity->type = $bill->type == 0 ? 13 : 14;
                        $activity->save();
                    }
                }
            }

            //add account_calculation
            if ($bill->account_id != null) {
                $ac = new AccountCalculation();
                $ac->device_id = Auth::user()->device_id;
                $ac->user_id = Auth::user()->id;
                $ac->account_id = $bill->account->id;
                $ac->type = $back->type == 0 ? 7 : ($back->type == 1 ? 8 : 6);
                $ac->value = $r->total_price;
                $ac->bill_id = $bill->id;
                $ac->account_after_this_action = $bill->account->account;
                $ac->note = $back->note;

                if ($back->type == 2) {//discount from account
                    $ac->rent = $r->total_price;
                    if ($bill->type == 0) {//bill buy
                        $ac->relation_account = 2;
                    } else {//bill sale
                        if ($bill->account->is_supplier) {
                            $ac->relation_account = 1;
                        } else {
                            $ac->relation_account = 2;
                        }
                    }
                }

                $ac->save();
            }


            //add activity if type back is replace
            if ($r->bill_back_type == 0) {
                $activity = new Activity();
                $activity->data = 'إضافة مرتجع نوعة إستبدال لفاتورة ' . ($bill->type == 0 ? ' شراء ' : ' بيع ') . 'رقم ' . $bill->id . ' بإجمالى ' . $back->total_price . 'ج';
                $activity->user_id = Auth::user()->id;
                $activity->device_id = Auth::user()->device_id;
                $activity->type = $bill->type == 0 ? 13 : 14;
                $activity->save();
            }

            Session::flash('success', 'تمت العملية بنجاح');
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
//            Session::flash('fault',$e->getMessage());
//            return back();
//            throw $e;
        }
        DB::commit();
        return back();
    }
}

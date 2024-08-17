<?php

namespace App\Console\Commands;

use App\Account;
use App\Backup;
use App\Barcode;
use App\BillMessage;
use App\BillPrint;
use App\Device;
use App\DeviceStoke;
use App\EmpJop;
use App\Permit;
use App\ProductCategory;
use App\ProductUnit;
use App\Setting;
use App\Stoke;
use App\StokePlaceName;
use App\StokeProductPlace;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class createData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:createData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //create device to allow this pc to use this project
        $s=Device::count();
        if ($s==0){
            $z=new Stoke();
            $z->name='المخزن الرئيسى';
            $z->save();

            $t = new Device();
            $t->mac='80-C1-6E-EB-44-E8';
            $t->hash_check = \Hash::make('80-C1-6E-EB-44-E8');
            $t->name = 'الجهاز الرئيسى';
            $t->default_stoke = 1;
            $t->save();

            $r=new DeviceStoke();
            $r->device_id=$t->id;
            $r->stoke_id=$z->id;
            $r->save();


            $t=new Device();
            $t->mac='DC-4A-3E-8C-27-14';
            $t->hash_check=\Hash::make('DC-4A-3E-8C-27-14');
            $t->name='جهاز 2';
            $t->save();

        }

        //create user if no user
        $u=User::count();
        if ($u==0){
            $user=new User();
            $user->name='VIP Account Ease';
            $user->type=1;//admin user
            $user->email='123';
            $user->password=\Hash::make('123');
            $user->device_id=1;
            $user->save();
        }

        //create backup if no backups pass
        $b=Backup::count();
        if ($b==0){
            $t=new BackUp();
            $t->pass='c:';
            $t->createBackUpEvery='1';
            $dayCreate= date('Y-m-d', strtotime(date('Y-m-d').'+1 days'));
            $t->dayCreate=$dayCreate;
            $t->type=0;
            $t->save();
        }

        //create setting if no backups pass
        $s=Setting::count();
        if ($s==0){
            $t=new Setting();
            $t->save();
        }

        //create barcode
        $barcode=Barcode::count();
        if ($barcode==0){
            $t=new Barcode();
            $t->user_id=1;
            $t->save();
        }

        //create print data
        $p=new BillPrint();
        $p->save();

        //create test data
        $productUnit=ProductUnit::count();
        if ($productUnit==0){
            $t=new ProductUnit();
            $t->name='قطعة';
            $t->default_value_for_min_qte=5;
            $t->save();

            $t=new ProductUnit();
            $t->name='شنطة';
            $t->default_value_for_min_qte=5;
            $t->save();

            $t=new ProductUnit();
            $t->name='علبة';
            $t->default_value_for_min_qte=5;
            $t->save();

            $t=new ProductUnit();
            $t->name='كيلو';
            $t->default_value_for_min_qte=5;
            $t->save();

            $t=new ProductUnit();
            $t->name='كرتونة';
            $t->default_value_for_min_qte=5;
            $t->save();

            $t=new ProductUnit();
            $t->name='بالة';
            $t->default_value_for_min_qte=5;
            $t->save();

            $t=new ProductUnit();
            $t->name='طن';
            $t->default_value_for_min_qte=5;
            $t->save();

            $t=new ProductUnit();
            $t->name='زجاجة';
            $t->default_value_for_min_qte=5;
            $t->save();

            $z=new ProductCategory();
            $z->name='بدون';
            $z->save();

            $f=new EmpJop();
            $f->name='بدون';
            $f->save();


            $i=new BillMessage();
            $i->name='شكراً لزيارتكم';
            $i->save();

            $i=new BillMessage();
            $i->name='سعداء بخدمتكم';
            $i->save();

            $i=new BillMessage();
            $i->name='نسخة من الفاتورة بعد التعديل';
            $i->save();

            $i=new BillMessage();
            $i->name='نسعى لنكون الأفضل';
            $i->save();

            $i=new BillMessage();
            $i->name='برجاء مراجعة الحساب';
            $i->save();

            $i=new BillMessage();
            $i->name='برجاء الإحتفاظ بالفاتورة';
            $i->save();

            /*$z=new Stoke();
            $z->name='مخزن 2';
            $z->save();

            $z=new StokePlaceName();
            $z->name='الرف الأول';
            $z->save();

            $z=new StokePlaceName();
            $z->name='الرف الثانى';
            $z->save();*/
        }

        //add check active
        $checkPermit=Permit::count();
        if ($checkPermit==0){
            $c=new Permit();
            $c->mange_stoke=Hash::make('mange_stoke');
            $c->place_product=Hash::make('place_product');
            $c->sup_cust=Hash::make('sup_cust');
            $c->product_make=Hash::make('product_make');
            $c->product_no_qte=Hash::make('product_no_qte');
            $c->use_barcode=Hash::make('use_barcode');
            $c->use_barcode2=Hash::make('use_barcode2');
            $c->use_barcode3=Hash::make('use_barcode3');
            $c->bill_design=Hash::make('bill_design');
            $c->use_expenses=Hash::make('use_expenses');
            $c->use_exit_deal=Hash::make('use_exit_deal');
            $c->use_emp=Hash::make('use_emp');
            $c->account_product_move=Hash::make('account_product_move');
            $c->use_visit=Hash::make('use_visit');
            $c->only_product_no_qte=Hash::make('false');
            $c->use_price2=Hash::make('use_price2');
            $c->use_price3=Hash::make('use_price3');
            $c->use_price4=Hash::make('use_price4');

            $c->save();
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Billing;
use Illuminate\Http\Request;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class HomeController extends Controller
{
    public function index(){
        //pencaharian durung
        if(auth()->user()->role->role_name=='Admin'){
            $billings = Billing::get();
            return view('home.admin',compact('billings'));
        }else{
            $billings = Billing::where('user_id','=', auth()->user()->user_id)->get();
            if($billings->count()>0){
                return view('home.pembayaran',compact('billings'));
                // echo 'aaaaa';
                // dd($billings);
            }else{
                $services = Service::orderBy('created_at', 'asc')->get();
                return view('home.index',compact('services'));
            }

        }

    }

    public function aktif(Request $request)
    {

        if(auth()->user()->role->role_name!='Admin'){
            abort(404,"Sorry, You can do this actions");
        }

        $tglhariini=date('Y-m-d');
        $tambahbulan = date('Y-m-d', strtotime('+'.$request->qty_month.' month', strtotime($tglhariini)));

        $request['expired_date']=$tambahbulan;
        $billings = billing::findOrFail($request->billing_id);
        // dd($request->all());
        $billings->update([
                        'expired_date' => $request->expired_date,
                        'billing_status' => $request->billing_status,
                        'verify_by' => auth()->user()->user_id,
                    ]);

        Alert::info('Update', 'Data Berhasil Di'.$request->billing_status);
        return back();
    }

    public function uploadbuktipembayaran(Request $request){
        $this->validate($request, [
            'file' => 'required|file|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $file = $request['file'];
		$nama_file = time()."_".$file->getClientOriginalName();
      	        // isi dengan nama folder tempat kemana file diupload
		$tujuan_upload = 'uploadgambar/bukti';
        $file->move($tujuan_upload,$nama_file);

        $billings = billing::findOrFail($request->billing_id);
        $billings->update([
            'billing_photo' => $nama_file,
        ]);

        Alert::info('Update', 'Bukti Pembayaran Berhasil Di Upload, mohon tunggu konfirmasi dari kami. Terimakasih.');
        return back();

    }

    public function store(){


    }

    public function update(){


    }

    public function billing(Request $request)
    {
        // $request->service_id;
        $services = Service::where('service_id','=', $request->service_id)->get();
        // dd($services);
        return view('home.billing',compact('services'));
    }

    public function payment(Request $request)
    {
        $banks = Bank::get();
        $services = Service::where('service_id','=', $request->service_id)->get();
         $jum['min_user']=$request->min_user;
        $jum['qty_month']=$request->qty_month;
        // dd($services);//.tampilkan yang ada dibilling dan simpan
       return view('home.payment',compact('services', 'jum', 'banks'));
    }

    public function register(Request $request)
    {
        //  'verify_by',
        // 'billing_photo'
        // $cek = Carbon::now();
        $tglhariini=date('Y-m-d');
        $tglditambah7hari = date('Y-m-d', strtotime('+7 days', strtotime($tglhariini)));
        // echo 'tanggal hari ini = '.$tglhariini.'<br> tglditambah3hari = '.$tglditambah3hari.'<br> cek carbon = '.$cek;
        $services = Service::findOrFail($request->service_id);;
        // echo $services['service_price'];
        //expired date hari ini + 3 hari kalau update ganti hari
        //status billing awalnya non aktif
        //date transaction hari ini kalau update ganti hari
        //photo itu diisi(edit) setelah pembayaran
        //method billing nggambil dari form
        //billing_total = minuser*qtymon*hargaservice
        //veryfi nanti setelah daftar yang ngisi itu adalah admin dan nanti aktif
        $request['billing_id']=md5(Carbon::now());
        $request['date_transaction']=$tglhariini;
        $request['expired_date']=$tglditambah7hari;
        $request['billing_price']=$services['service_price'];
        $request['billing_total']=$request->min_user*$request->qty_month*$services['service_price'];
        // $jum['qty_month']=$request->qty_month;
        // dd($request);//.tampilkan yang ada dibilling dan simpan
    //    return view('home.payment',compact('services', 'jum', 'banks'));
        //
        billing::create([
            'billing_price' => $request->billing_price,
            'date_transaction' => $request->date_transaction,
            'qty_month' => $request->qty_month,
            'billing_max_user' => $request->min_user,
            'billing_total' => $request->billing_total,
            'billing_method' => $request->billing_method,
            'billing_id' => $request->billing_id,
            'billing_status' => 'Non Aktif',
            'expired_date' => $request->expired_date,
            'service_id' => $request->service_id,
            'user_id' => auth()->user()->user_id,
        ]);
        //
        return redirect()->route('home');
    }
}

<?php
namespace Modules\Api\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Booking\Models\Service;
use App\Models\Transactions;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Modules\Booking\Models\Payment;
use App\Models\UserTransaction;
use App\Models\UserTransactionDetails;
use App\Helpers\CodeHelper;
use Modules\Booking\Models\Booking;
class SearchController extends Controller
{

    public function search($type = ''){
        $type = $type ? $type : request()->get('type');
        if(empty($type))
        {
            return $this->sendError(__("Type is required"));
        }

        $class = get_bookable_service_by_id($type);
        if(empty($class) or !class_exists($class)){
            return $this->sendError(__("Type does not exists"));
        }

        $rows = call_user_func([$class,'search'],request());
        $total = $rows->total();
        return $this->sendSuccess(
            [
                'total'=>$total,
                'total_pages'=>$rows->lastPage(),
                'data'=>$rows->map(function($row){
                    return $row->dataForApi();
                }),
            ]
        );
    }


    public function searchServices(){
        $rows = call_user_func([new Service(),'search'],request());
        $total = $rows->total();
        return $this->sendSuccess(
            [
                'total'=>$total,
                'total_pages'=>$rows->lastPage(),
                'data'=>$rows->map(function($row){
                    return $row->dataForApi();
                }),
            ]
        );
    }

    public function getFilters($type = ''){
        $type = $type ? $type : request()->get('type');
        if(empty($type))
        {
            return $this->sendError(__("Type is required"));
        }
        $class = get_bookable_service_by_id($type);
        if(empty($class) or !class_exists($class)){
            return $this->sendError(__("Type does not exists"));
        }
        $data = call_user_func([$class,'getFiltersSearch'],request());
        return $this->sendSuccess(
            [
                'data'=>$data
            ]
        );
    }

    public function getFormSearch($type = ''){
        $type = $type ? $type : request()->get('type');
        if(empty($type))
        {
            return $this->sendError(__("Type is required"));
        }
        $class = get_bookable_service_by_id($type);
        if(empty($class) or !class_exists($class)){
            return $this->sendError(__("Type does not exists"));
        }
        $data = call_user_func([$class,'getFormSearch'],request());
        return $this->sendSuccess(
            [
                'data'=>$data
            ]
        );
    }

    public function detail($type = '',$id = '')
    {
        if(empty($type)){
            return $this->sendError(__("Resource is not available"));
        }
        if(empty($id)){
            return $this->sendError(__("Resource ID is not available"));
        }

        $class = get_bookable_service_by_id($type);
        if(empty($class) or !class_exists($class)){
            return $this->sendError(__("Type does not exists"));
        }

        $row = $class::find($id);
        if(empty($row))
        {
            return $this->sendError(__("Resource not found"));
        }

        return $this->sendSuccess([
            'data'=>$row->dataForApi(true)
        ]);

    }

    public function checkAvailability(Request $request , $type = '',$id = ''){
        if(empty($type)){
            return $this->sendError(__("Resource is not available"));
        }
        if(empty($id)){
            return $this->sendError(__("Resource ID is not available"));
        }
        $class = get_bookable_service_by_id($type);
        if(empty($class) or !class_exists($class)){
            return $this->sendError(__("Type does not exists"));
        }
        $classAvailability = $class::getClassAvailability();
        $classAvailability = new $classAvailability();
        $request->merge(['id' => $id]);
        if($type == "hotel"){
            $request->merge(['hotel_id' => $id]);
            return $classAvailability->checkAvailability($request);
        }
        return $classAvailability->loadDates($request);
    }


    public function UpdateTransactions(Request $request,$id)
    {
        $data=[
            "transaction_id"=>str_replace('_',' ',$request->transaction_id),
            "order_id"=>$request->order_id,
            "payment_type"=>str_replace('_',' ',$request->payment_type),
            "payment_date"=>$request->payment_date,
            "credit_card"=>$request->code,
            "amount"=>$request->pay_amount,
            "due_amount"=>$request->due_amount,
            "full_amount"=>$request->full_amount,
            "status"=>$request->status
        ];
        $payment = Payment::where('id', $id)->first();
        if(!empty($payment->booking_id))
        {
            Transactions::Create($data);           
        }

        $wallet= Wallet::where('holder_type', 'App\User') ->where('holder_id', $payment['create_user'])->first();

         if(!empty($payment))
         {
            $data=[
                'payable_type'=>'App\User', 	
                'payment_id'=>$request->payment_id,
                'wallet_id'=>$wallet['id'],
                'type'=>'partial',	
                'amount'=>$request->pay_amount,	
                'confirmed'=>1,
                'meta'=>$request->payment_type,
                'full_amount'=>$request->full_amount,	
                'user_id'=>$payment['create_user'],	
                'booking_id'=>$payment['booking_id'],
                'is_debit'=>1,
                'reference_id'=>$request->transaction_id,
                'status'=>$request->status

            ];
    
            UserTransactionDetails::Create($data);
         }

    }


    public function UpdateUserCredit(Request $request,$id)
    {
        $payment = Payment::where('id', $id)->first();
        if(!empty($payment))
        {
            $wallet= Wallet::where('holder_type', 'App\User') ->where('holder_id', $payment['create_user'])->first();
            $wallet->balance=$request->credits; 
            $wallet->update();
        }
        
    }

    public function updateUserCreditByUser(Request $request,$id)
    {
        if(!empty($id))
        {
            $wallet= Wallet::where('holder_type', 'App\User') ->where('holder_id', $id)->first();
            $wallet->balance=$request->credits; 
            $wallet->update();
        }
        
    }

    public function updatePaymentStatus(Request $request,$id)
    {
        if(!empty($id))
        {
            $booking= Booking::where('id', $id)->first();
            $booking->status=$request->status;
            $booking->update();
        }
        
    }

  // Incompleted,Partially Paid
   

    public function UpdateBuyCredit(Request $request,$id)
    {
        $payment = Payment::where('id', $id)->first();
        $wallet= Wallet::where('holder_type', 'App\User') ->where('holder_id', $payment['create_user'])->first();

         if(!empty($payment))
         {
            $data=[
                'payable_type'=>'App\User', 	
                'payment_id'=>$request->payment_id,
                'wallet_id'=>$wallet['id'],
                'type'=>($request->full_amount==$request->pay_amount)?'single':'partial',	
                'amount'=>$request->pay_amount,	
                'confirmed'=>1,
                'meta'=>$request->payment_type,
                'full_amount'=>$request->full_amount,	
                'user_id'=>$payment['create_user'],	
                'booking_id'=>'',
                'is_debit'=>0,
                'reference_id'=>$request->transaction_id,
                'status'=>$request->status

            ];
    
            UserTransactionDetails::Create($data);

         }
        
    }

    
}

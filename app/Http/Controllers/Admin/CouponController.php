<?php

namespace Fickrr\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Fickrr\Http\Controllers\Controller;
use Session;
use Fickrr\Models\Coupon;
use Fickrr\Models\Members;
use Fickrr\Models\Settings;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Auth;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
		
    }
	
	
	
	/* coupon */
	
	public function view_coupon()
    {
        
		$couponData['view'] = Coupon::getallCoupon();
		return view('admin.coupons',[ 'couponData' => $couponData]);
    }
	
	
	public function add_coupon()
	{
	   $getvendor['view'] = Members::getvendorData();
	   $data = array('getvendor' => $getvendor);
	   return view('admin.add-coupon')->with($data);
	}
	
	
	public function save_coupon(Request $request)
	{
 
    
         $coupon_code = $request->input('coupon_code');
		 $user_id = $request->input('user_id');
		 $discount_type = $request->input('discount_type');
         $coupon_start_date = $request->input('coupon_start_date');
		 $coupon_end_date = $request->input('coupon_end_date');
		 $coupon_value = $request->input('coupon_value');
		 $coupon_status = $request->input('coupon_status');
		 	
		 
		
		 
		 
         
		 $request->validate([
							'coupon_code' => 'required',
							'discount_type' => 'required',
							'coupon_value' => 'required',
							
         ]);
		 $rules = array(
				'coupon_code' => ['required',  Rule::unique('coupon') -> where(function($sql){ $sql->where('coupon_id','!=','');})],
				
	     );
		 
		 $messsages = array(
		      
	    );
		 
		$validator = Validator::make($request->all(), $rules,$messsages);
		
		if ($validator->fails()) 
		{
		 $failedRules = $validator->failed();
		 return back()->withErrors($validator);
		} 
		else
		{
		
		if($discount_type == 'percentage' && $coupon_value > 100)
		{
		  return redirect()->back()->with('error', 'Please enter coupon value below 100.');
		}
		else
		{ 
		$data = array('user_id' => $user_id, 'coupon_code' => $coupon_code, 'discount_type' => $discount_type, 'coupon_value' => $coupon_value, 'coupon_start_date' => $coupon_start_date, 'coupon_end_date' => $coupon_end_date, 'coupon_status' => $coupon_status);
        Coupon::insertCoupon($data);
        return redirect('/admin/coupons')->with('success', 'Insert successfully.');
		}
            
 
       } 
     
    
  }
  
  
    public function delete_coupon($coupon_id){
     
	   $couponid = base64_decode($coupon_id);
      	  
      Coupon::deleteCoupon($couponid);
	  
	  return redirect()->back()->with('success', 'Delete successfully.');

    
  }
  
  
  public function edit_coupon($coupon_id)
	{
	   $couponid = base64_decode($coupon_id);
	   $edit['value'] = Coupon::editCoupon($couponid);
	   $getvendor['view'] = Members::getvendorData();
	   return view('admin.edit-coupon', [ 'edit' => $edit, 'getvendor' => $getvendor]);
	}
	
	
	
	public function update_coupon(Request $request)
	{
	
	     $coupon_code = $request->input('coupon_code');
		 $user_id = $request->input('user_id');
		 $discount_type = $request->input('discount_type');
         $coupon_start_date = $request->input('coupon_start_date');
		 $coupon_end_date = $request->input('coupon_end_date');
		 $coupon_value = $request->input('coupon_value');
		 $coupon_status = $request->input('coupon_status');
		 $coupon_id = base64_decode($request->input('coupon_id'));	
		 
		
		 
		 
         
		 $request->validate([
							'coupon_code' => 'required',
							'discount_type' => 'required',
							'coupon_value' => 'required',
							
         ]);
		 $rules = array(
				'coupon_code' => ['required',  Rule::unique('coupon') ->ignore($coupon_id, 'coupon_id') -> where(function($sql){ $sql->where('coupon_status','!=','');})],
				
	     );
		 
		 $messsages = array(
		      
	    );
		 
		$validator = Validator::make($request->all(), $rules,$messsages);
		
		if ($validator->fails()) 
		{
		 $failedRules = $validator->failed();
		 return back()->withErrors($validator);
		} 
		else
		{
		
		if($discount_type == 'percentage' && $coupon_value > 100)
		{
		  return redirect()->back()->with('error', 'Please enter coupon value below 100.');
		}
		else
		{ 
		$data = array('user_id' => $user_id, 'coupon_code' => $coupon_code, 'discount_type' => $discount_type, 'coupon_value' => $coupon_value, 'coupon_start_date' => $coupon_start_date, 'coupon_end_date' => $coupon_end_date, 'coupon_status' => $coupon_status);
        Coupon::updateCoupon($coupon_id, $data);
        return redirect('/admin/coupons')->with('success', 'Update successfully.');
		}
            
 
       } 
     
       
	
	
	}
	
	
	/* coupon */
	
	
	
	
  
	
	
	
}

<?php

namespace Fickrr\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Fickrr\Http\Controllers\Controller;
use Session;
use Fickrr\Models\Currencies;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

class CurrencyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
		
    }
	
	
	
	public function view_currencies()
	{
	  
	  $currencies = Currencies::viewCurrency(); 
	  $data = array('currencies' => $currencies);
	  return view('admin.currencies')->with($data);
	
	}
	
	public function add_currency()
	{
	   
	   return view('admin.add-currency');
	}
	
	
	public function save_currency(Request $request)
	{
 
         $currency_name = $request->input('currency_name');
         $currency_code = strtoupper($request->input('currency_code'));
		 $currency_symbol = $request->input('currency_symbol');
		 $currency_token = $this->generateRandomString();
		 $currency_order = $request->input('currency_order');
		 $currency_default = $request->input('currency_default');
		 $currency_status = $request->input('currency_status'); 
		 $currency_rate = $request->input('currency_rate');        
         
		 $request->validate([
							'currency_code' => 'required',
							
							
         ]);
		 $rules = array(
				
				'currency_code' => ['required', 'max:3', Rule::unique('currencies') -> where(function($sql){ $sql->where('currency_code','!=','');})],
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
		
				 
		$data = array('currency_token' => $currency_token, 'currency_name' => $currency_name, 'currency_code' => $currency_code, 'currency_symbol' => $currency_symbol, 'currency_order' => $currency_order, 'currency_default' => $currency_default, 'currency_status' => $currency_status, 'currency_rate' => $currency_rate);
 
            
            Currencies::saveCurrency($data);
            return redirect('/admin/currencies')->with('success', 'Insert successfully.');
            
 
       } 
     
    
  }
  
	
	
	
	public function edit_currency($token)
	{
	   $edit = Currencies::singleCurrency($token);
	  return view('admin.edit-currency',[ 'edit' => $edit]);
	}
	
	public function generateRandomString($length = 25) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
    }
	
	public function update_currency(Request $request)
	{
	
	   $currency_name = $request->input('currency_name');
       $currency_code = strtoupper($request->input('currency_code'));
	   $currency_symbol = $request->input('currency_symbol');
	   $currency_token = $request->input('currency_token');
	   $currency_order = $request->input('currency_order');
	   $currency_default = $request->input('currency_default');
	   $currency_status = $request->input('currency_status'); 	   
	    $currency_rate = $request->input('currency_rate'); 
		
	   $request->validate([
							
							
							
							
         ]);
		 $rules = array(
				
				/*'language_code' => ['required', Rule::unique('languages') ->ignore($language_token, 'language_token') -> where(function($sql){ $sql->where('language_status','!=','');})],*/
				
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
	        
		      $data = array('currency_name' => $currency_name, 'currency_code' => $currency_code, 'currency_symbol' => $currency_symbol, 'currency_order' => $currency_order, 'currency_default' => $currency_default, 'currency_status' => $currency_status, 'currency_rate' => $currency_rate);
			  
			   
			   
			   
			   if($currency_default == 1)
			   {  
			       $record = array('currency_default' => 0);
			       Currencies::removeDefaultCurrency($currency_token,$record);
			   }
			   
			   Currencies::updateCurrency($currency_token,$data);
			   
			  		   
			   return redirect('/admin/currencies')->with('success', 'Updated successfully.');
			
			   
			   
		}
	
	
	
	}
	
	
	
	
	
	
	public function delete_currency($token)
	{
	   $encrypter = app('Illuminate\Contracts\Encryption\Encrypter');
	   $currency_token   = $encrypter->decrypt($token);
	   Currencies::deleteCurrency($currency_token);
	   
	  
	  return redirect()->back()->with('success', 'Delete successfully.');
	
	}
	
	
	
}

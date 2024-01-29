<?php

namespace Fickrr\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Fickrr\Models\Settings;
use Auth;
use Storage;
use Session;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;


class Items extends Model
{
    
	/* items */
  protected $table = 'items';
 
   public function Ratings()
    {
        return $this->hasMany(Ratings::class, 'or_item_id', 'item_id');
    } 
	
	
	 public static function deleteItemtype($id,$data){
    
		
	DB::table('item_type')
      ->where('item_type_id', $id)
      ->update($data);
	
  }
  
  public static function checkOrder($order_id,$session_id,$order_status,$updatedata)
   {
    DB::table('item_order')
	  ->where('session_id', $session_id)
	  ->where('ord_id', $order_id)
	  ->where('order_status', $order_status)
	  ->update($updatedata);
   }
  
  public static function changeOrder($session_id,$updata)
   {
    DB::table('item_order')
	  ->where('session_id', $session_id)
	  ->where('order_status', 'pending')
	  ->update($updata);
   }
  
  public static function searchentireItem($search)
  {
    $sid = 1;
	$setting['setting'] = Settings::editGeneral($sid);
	$site_item_per_page = $setting['setting']->site_item_per_page;
    $value=DB::table('items')->select('items.item_thumbnail','items.item_name','items.item_featured','items.item_token','items.free_download','items.item_flash_request','items.item_flash','items.item_slug','users.username','items.item_status','items.item_category','items.item_category_parent','items.item_category_type', 'items.subscription_item')->join('users','users.id','items.user_id')->where('items.drop_status','=','no')->where('items.item_name', 'LIKE', "%$search%")->orderBy('items.item_id', 'desc')->paginate($site_item_per_page); 
    return $value;
	
  }	
  
  
  public static function forceDATA($session_id)
  {
     DB::table('items_data')->where('session_id', '=', $session_id)->delete();	
  }
  
  public static function autoSearch($query)
  {
    
    $value=DB::table('items')->where('item_name', 'LIKE', '%'. $query. '%')->where('drop_status','=','no')->where('item_status','=',1)->orderBy('item_name', 'asc')->get(); 
    return $value;
	
  }
  
  public static function proddataSave($data){
   
      DB::table('items_data')->insert($data);
     
 
    }	
  
  public static function getProdutData($session_id)
  {
    /*$query = ".zip";
	$query2 = ".mp4";
	$query3 = ".mp3";
    $value=DB::table('items_data')->where('session_id','=', $session_id)->where('original_file_name', 'NOT LIKE', '%'. $query. '%')->where('original_file_name', 'NOT LIKE', '%'. $query2. '%')->where('original_file_name', 'NOT LIKE', '%'. $query3. '%')->orderBy('itm_id', 'asc')->get();
	return $value;*/
	
	$qry = ".jpeg,.jpg,.png,.webp";
	$terms = explode(',',$qry);
	$value=DB::table('items_data')
	       ->where('session_id','=', $session_id)
		   ->where(function($value) use($terms) {
                        foreach($terms as $term) {
                            $value->orWhere('original_file_name', 'like', "%$term%");
                        };
                    })
		   ->orderBy('itm_id', 'asc')->get();
	return $value;
	
  }
  
  public static function getProdutZip($session_id)
  { 
    $query = ".zip";
    $value=DB::table('items_data')->where('session_id','=', $session_id)->where('original_file_name', '!=', '')->orderBy('itm_id', 'asc')->get();
	return $value;
	
	/*$query = ".zip";
    $value=DB::table('items_data')->where('session_id','=', $session_id)->where('original_file_name', 'LIKE', '%'. $query. '%')->orderBy('itm_id', 'asc')->get();
	return $value;*/
	
  }
  
  public static function getProdutMP4($session_id)
  { 
    $query = ".mp4";
    $value=DB::table('items_data')->where('session_id','=', $session_id)->where('original_file_name', 'LIKE', '%'. $query. '%')->orderBy('itm_id', 'asc')->get();
	return $value;
  }
  
  public static function getProdutMP3($session_id)
  { 
    $query = ".mp3";
    $value=DB::table('items_data')->where('session_id','=', $session_id)->where('original_file_name', 'LIKE', '%'. $query. '%')->orderBy('itm_id', 'asc')->get();
	return $value;
  }
  
    public static function checkPurchased($logged,$token)
  {

    $get=DB::table('item_order')->where('item_token','=', $token)->where('user_id','=', $logged)->where('approval_status','!=', 'payment released to buyer')->get();
	$value = $get->count(); 
    return $value;
	
  }
	
	public static function getallItems()
	{
	  $value=DB::table('items')->where('drop_status','=','no')->where('item_status','=',1)->orderBy('item_id', 'desc')->get();
	  return $value;
	}
	
	public static function GetAllProducts()
  {

    $value=DB::table('items')->get();
	return $value;
	
  }	
  
  public static function getfeaturedUser($user_id)
  {

    $value=DB::table('items')->where('user_id','=',$user_id)->where('drop_status','=','no')->where('item_status','=',1)->where('item_featured','=','yes')->get()->groupBy('user_id'); 
    return $value;
	
  }	
  
  public static function getfreeUser($user_id)
  {

    $value=DB::table('items')->where('user_id','=',$user_id)->where('drop_status','=','no')->where('item_status','=',1)->where('free_download','=',1)->get()->groupBy('user_id'); 
    return $value;
	
  }	
  
  
  public static function getTrendUser($user_id)
  {
    $today = date('Y-m-d', strtotime('today - 30 days'));
    $value=DB::table('item_order')->where('item_user_id','=',$user_id)->where('order_status','=','completed')->where('start_date','>',$today)->get()->groupBy('item_user_id'); 
    return $value;
	
  }	
	
	
  public static function getmanageitemData()
  {
    $user_id = Auth::user()->id;
	$sid = 1;
	$setting['setting'] = Settings::editGeneral($sid);
	$site_item_per_page = $setting['setting']->site_item_per_page;
    $value=DB::table('items')->join('users','users.id','items.user_id')->where('items.user_id','=',$user_id)->where('items.item_status','!=',2)->where('items.drop_status','=','no')->orderBy('items.item_id', 'desc')->paginate($site_item_per_page); 
    return $value;
	
  }	
	
	
  
  public static function getitemData()
  {
    $user_id = Auth::user()->id;
    $value=DB::table('items')->join('users','users.id','items.user_id')->where('items.user_id','=',$user_id)->where('items.item_status','=',1)->where('items.drop_status','=','no')->orderBy('items.item_id', 'desc')->get(); 
    return $value;
	
  }
  
  public static function checkoutLevel($purchased_token)
  {

    $get=DB::table('item_checkout')->where('payment_token','!=',"")->where('purchase_token','=',$purchased_token)->get();
	$value = $get->count(); 
    return $value;
	
	
  }	
  
  public static function findProduct($product_token)
  {

    $get=DB::table('items')->where('item_token','=',$product_token)->get();
	$value = $get->count(); 
    return $value;
	
	
  }	
  
  public static function checkItemUser($user_id)
  {

    $get=DB::table('items')->where('user_id','=',$user_id)->where('drop_status','=','no')->get();
	$value = $get->count(); 
    return $value;
	
	
  }	
  
  public static function getItemStorage($user_id)
  {
    
    $value=DB::table('items')->where('user_id','=',$user_id)->where('drop_status','=','no')->get(); 
    return $value;
	
  }	
  /*public static function topUsers()
  {
    
    $value=DB::table('users')->leftJoin('item_order','item_order.item_user_id','users.id')->leftJoin('country','country.country_id','users.country')->where('users.drop_status','=','no')->where('users.id','!=',1)->groupBy('item_order.item_user_id')->get(); 
    return $value;
	
  }	*/
  
  
  public static function getgroupItems()
  {

    $value=DB::table('items')->where('drop_status','=','no')->where('item_status','=',1)->get()->groupBy('user_id'); 
    return $value;
	
  }	
  
  
   public static function getgroupSale()
  {

    $value=DB::table('item_order')->where('order_status','=','completed')->get()->groupBy('item_user_id'); 
    return $value;
	
  }
  
  public static function gettypeItemNew()
  {
    
    $value=DB::table('item_type')->where('item_type_drop_status','=','no')->orderBy('item_type_id', 'asc')->get(); 
    return $value;
	
  }	
  
  public static function gettypeItem()
  {
    
    $value=DB::table('item_type')->where('item_type_status','=',1)->where('item_type_drop_status','=','no')->orderBy('item_type_id', 'asc')->get(); 
    return $value;
	
  }	
  
  
  public static function gettypeStatus()
  {
    
    $value=DB::table('item_type')->where('item_type_status','=',1)->where('item_type_drop_status','=','no')->orderBy('item_type_id', 'asc')->get(); 
    return $value;
	
  }
  
  
  public static function dropItems($item_data,$user_id)
  {
    DB::table('items')
      ->where('user_id', $user_id)
	  ->update($item_data);
  }
  
  
  public static function updateFlash($data)
  {
    DB::table('items')
      ->where('item_status', 1)
	  ->where('drop_status', 'no')
      ->update($data);
  }	
  
  
  public static function updateFree($data)
  {
    DB::table('items')
      ->where('item_status', 1)
	  ->where('drop_status', 'no')
      ->update($data);
  }	
  
  
  public static function updateitemType($id,$data)
  {
    DB::table('item_type')
      ->where('item_type_id', $id)
      ->update($data);
  }
  
  
  public static function searchtrashItem($search)
  {
    $sid = 1;
	$setting['setting'] = Settings::editGeneral($sid);
	$site_item_per_page = $setting['setting']->site_item_per_page;
    $value=DB::table('items')->select('items.item_thumbnail','items.item_name','items.item_featured','items.item_token','items.free_download','items.item_flash_request','items.item_flash','items.item_slug','users.username','items.item_status','items.item_category','items.item_category_parent','items.item_category_type', 'items.subscription_item')->join('users','users.id','items.user_id')->where('items.drop_status','=','yes')->where('items.item_name', 'LIKE', "%$search%")->orderBy('items.item_id', 'desc')->paginate($site_item_per_page); 
    return $value;
	
  }	
  
  public static function searchentireOrder($search)
  {
    $sid = 1;
	$setting['setting'] = Settings::editGeneral($sid);
	$site_item_per_page = $setting['setting']->site_item_per_page;
    $value=DB::table('item_checkout')
	       ->join('users','users.id','item_checkout.user_id')
		   ->where(function ($query) use ($search) { 
		   $query->where('item_checkout.purchase_token', 'LIKE', "%$search%");
		   $query->orWhere('users.username', 'LIKE', "%$search%");
		   })->orderBy('item_checkout.chout_id', 'desc')
		   ->paginate($site_item_per_page); 
    return $value;
	
  }	
  
  public static function getorderItem()
  {
    $sid = 1;
	$setting['setting'] = Settings::editGeneral($sid);
	$site_item_per_page = $setting['setting']->site_item_per_page;
    $value=DB::table('item_checkout')->select('item_checkout.purchase_token','users.username','item_checkout.vendor_amount','item_checkout.admin_amount','item_checkout.processing_fee','item_checkout.vat_price','item_checkout.currency_type','item_checkout.payment_type','item_checkout.payment_token','item_checkout.payment_status')->join('users','users.id','item_checkout.user_id')->orderBy('item_checkout.chout_id', 'desc')->paginate($site_item_per_page); 
    return $value;
	
  }	
  
  public static function getentireItem()
  {
    $sid = 1;
	$setting['setting'] = Settings::editGeneral($sid);
	$site_item_per_page = $setting['setting']->site_item_per_page;
    $value=DB::table('items')->select('items.item_thumbnail','items.item_name','items.item_featured','items.item_token','items.free_download','items.item_flash_request','items.item_flash','items.item_slug','users.username','items.item_status','items.subscription_item')->join('users','users.id','items.user_id')->where('items.drop_status','=','no')->orderBy('items.item_id', 'desc')->paginate($site_item_per_page); 
    return $value;
	
  }	
  
  public static function getTrashItem()
  {
    $sid = 1;
	$setting['setting'] = Settings::editGeneral($sid);
	$site_item_per_page = $setting['setting']->site_item_per_page;
    $value=DB::table('items')->select('items.item_thumbnail','items.item_name','items.item_featured','items.item_token','items.free_download','items.item_flash_request','items.item_flash','items.item_slug','users.username','items.item_status','items.subscription_item')->join('users','users.id','items.user_id')->where('items.drop_status','=','yes')->orderBy('items.item_id', 'desc')->paginate($site_item_per_page); 
    return $value;
	
  }	
  
  public static function getuserItem($user_id)
  {
    
    $value=DB::table('items')->join('users','users.id','items.user_id')->where('items.user_id','=',$user_id)->where('items.item_status','=',1)->where('items.drop_status','=','no')->orderBy('items.item_id', 'desc')->get(); 
    return $value;
	
  }	
  	
	
  public static function insertItemtype($data)
  {
   
      DB::table('item_type')->insert($data);
     
 
  }	
  
  /* withdrawal methods */
  
  public static function saveWithMethod($data)
  {
   
      DB::table('withdrawal_methods')->insert($data);
     
 
  }	
  
  public static function getWithMethod()
  {
    
    $value=DB::table('withdrawal_methods')->orderBy('withdrawal_order', 'asc')->get(); 
    return $value;
	
  }
  
  
  public static function editWithMethod($wm_id)
  {
    $value = DB::table('withdrawal_methods')
      ->where('wm_id', $wm_id)
      ->first();
	return $value;
  }
  
  public static function keyWithMethod($key)
  {
    $value = DB::table('withdrawal_methods')
      ->where('withdrawal_key', $key)
      ->first();
	return $value;
  }
  
  
  
  public static function typeIDdata($id)
  {
    $value = DB::table('item_type')
      ->where('item_type_id', $id)
      ->first();
	return $value;
  }
  
  public static function typeIDCount($id)
  {
    
    $get=DB::table('item_type')->where('item_type_id', $id)->get(); 
	$value = $get->count();
    return $value;
	
  }	
  
  public static function updateWithMethod($wm_id, $data){
    DB::table('withdrawal_methods')
      ->where('wm_id', $wm_id)
      ->update($data);
  }
  
  public static function getAllWithMethod()
  {
    
    $value=DB::table('withdrawal_methods')->where('withdrawal_status','=',1)->orderBy('withdrawal_order', 'asc')->get(); 
    return $value;
	
  }
  /* withdrawal methods */
  
  public static function saveAttribute($data)
  {
   
      DB::table('items_attributes')->insert($data);
     
 
  }
  
  public static function dropAttribute($item_token){
    
		
	DB::table('items_attributes')->where('item_token', '=', $item_token)->delete();	
	
	
  }
	
  
  public static function saveitemData($data)
  {
   
      DB::table('items')->insert($data);
     
 
  }
  
  
  public static function updateAttribute($attr_id,$data)
  {
    DB::table('attributes')
      ->where('attr_id', $attr_id)
      ->update($data);
  }
  
  public static function updateitemData($item_token,$data)
  {
    DB::table('items')
      ->where('item_token', $item_token)
      ->update($data);
  }
  
  
  public static function rejectitemData($item_token,$reject_data)
  {
    DB::table('items')
      ->where('item_token', $item_token)
      ->update($reject_data);
  }
  
  
  
  public static function fileDeleted($filename)
  {
    $session_id = Session::getId();
	$image = DB::table('items_data')->where('original_file_name', '=', $filename)->where('session_id', '=', $session_id)->first();
    $file= $image->item_file_name;
	$settings = DB::table('settings')->where('sid', 1)->first();
	/* wasabi */
	$wasabi_access_key_id = $settings->wasabi_access_key_id;
	$wasabi_secret_access_key = $settings->wasabi_secret_access_key;
	$wasabi_default_region = $settings->wasabi_default_region;
	$wasabi_bucket = $settings->wasabi_bucket;
	$wasabi_endpoint = 'https://s3.'.$wasabi_default_region.'.wasabisys.com';
	$raw_credentials = array(
									'credentials' => [
										'key' => $wasabi_access_key_id,
										'secret' => $wasabi_secret_access_key
									],
									'endpoint' => $wasabi_endpoint, 
									'region' => $wasabi_default_region, 
									'version' => 'latest',
									'use_path_style_endpoint' => true
								);
	$s3 = S3Client::factory($raw_credentials);
	/* wasabi */
	if($settings->site_s3_storage == 1)
	{
	    $exists = Storage::disk('s3')->has($file);
        if($exists)
		{
		  Storage::disk('s3')->delete($file); // s3
		  
	    }
		$drop = public_path().'/storage/items/'.$file;  // my server
        File::delete($drop);
		
	}
	else if($settings->site_s3_storage == 2)
	{
	  $s3->deleteObject(['Bucket' => $wasabi_bucket, 'Key' => $file]);
	   // wasabi
	  
	  $drop = public_path().'/storage/items/'.$file;  // my server
      File::delete($drop);
	}
	else if($settings->site_s3_storage == 3)
	{
	  Storage::disk('dropbox')->delete($file); // dropbox
	  $drop = public_path().'/storage/items/'.$file;  // my server
      File::delete($drop);
	}
	else if($settings->site_s3_storage == 4)
	{
	  Storage::disk('google')->delete($file); // google
	  $drop = public_path().'/storage/items/'.$file;  // my server
      File::delete($drop);
	}
	else
	{
	$drop = public_path().'/storage/items/'.$file;  // my server
    File::delete($drop);
	}
	DB::table('items_data')->where('original_file_name', '=', $filename)->where('session_id', '=', $session_id)->delete();	
	
	
  }
  
  public static function singleDroper($field,$item_token)
  {
    $settings = DB::table('settings')->where('sid', 1)->first();
	$image = DB::table('items')->where('item_token', '=', $item_token)->first();
	$file = $image->$field;
	/* wasabi */
	$wasabi_access_key_id = $settings->wasabi_access_key_id;
	$wasabi_secret_access_key = $settings->wasabi_secret_access_key;
	$wasabi_default_region = $settings->wasabi_default_region;
	$wasabi_bucket = $settings->wasabi_bucket;
	$wasabi_endpoint = 'https://s3.'.$wasabi_default_region.'.wasabisys.com';
	$raw_credentials = array(
									'credentials' => [
										'key' => $wasabi_access_key_id,
										'secret' => $wasabi_secret_access_key
									],
									'endpoint' => $wasabi_endpoint, 
									'region' => $wasabi_default_region, 
									'version' => 'latest',
									'use_path_style_endpoint' => true
								);
	$s3 = S3Client::factory($raw_credentials);
	/* wasabi */
	if($settings->site_s3_storage == 1)
	{
		   Storage::disk('s3')->delete($file);
		   
	}
    else if($settings->site_s3_storage == 2)
    {
		   
		   $s3->deleteObject(['Bucket' => $wasabi_bucket, 'Key' => $file]);
		  
	}
	else if($settings->site_s3_storage == 3)
	{
		   Storage::disk('dropbox')->delete($file);
		   
	}
	else if($settings->site_s3_storage == 4)
	{
		   Storage::disk('google')->delete($file);
		   
	}
	else
	{
	    $drop = public_path().'/storage/items/'.$file;  // my server
        File::delete($drop);
	}	
    
  }
  
  
  
  public static function fineDroper($field,$item_token)
  {
    $settings = DB::table('settings')->where('sid', 1)->first();
	$image = DB::table('items')->where('item_token', '=', $item_token)->first();
	$file = $image->$field;
	/* wasabi */
	$wasabi_access_key_id = $settings->wasabi_access_key_id;
	$wasabi_secret_access_key = $settings->wasabi_secret_access_key;
	$wasabi_default_region = $settings->wasabi_default_region;
	$wasabi_bucket = $settings->wasabi_bucket;
	$wasabi_endpoint = 'https://s3.'.$wasabi_default_region.'.wasabisys.com';
	$raw_credentials = array(
									'credentials' => [
										'key' => $wasabi_access_key_id,
										'secret' => $wasabi_secret_access_key
									],
									'endpoint' => $wasabi_endpoint, 
									'region' => $wasabi_default_region, 
									'version' => 'latest',
									'use_path_style_endpoint' => true
								);
	$s3 = S3Client::factory($raw_credentials);
	/* wasabi */
	if($settings->site_s3_storage == 1)
	{
		   Storage::disk('s3')->delete($file);
		   
	}
	else if($settings->site_s3_storage == 2)
	{
	   
	   $result = $s3->deleteObject(['Bucket' => $wasabi_bucket, 'Key' => $file]);
		   
	}
	else if($settings->site_s3_storage == 3)
	{
	   Storage::disk('dropbox')->delete($file);
		   
	}
	else if($settings->site_s3_storage == 4)
	{
		Storage::disk('google')->delete($file);
		   
	}
	else
	{
	   $drop = public_path().'/storage/items/'.$file;  // my server
       File::delete($drop);
	}  
    
  }
  
  
  
  
  
  
  public static function ssDeleted($filename)
  {
    $session_id = Session::getId();
	$image = DB::table('items_data')->where('original_file_name', '=', $filename)->where('session_id', '=', $session_id)->first();
    return $image;
  }
  
  public static function deleteimgdata($token)
  {
    
	    $settings = DB::table('settings')->where('sid', 1)->first();
		/* wasabi */
	$wasabi_access_key_id = $settings->wasabi_access_key_id;
	$wasabi_secret_access_key = $settings->wasabi_secret_access_key;
	$wasabi_default_region = $settings->wasabi_default_region;
	$wasabi_bucket = $settings->wasabi_bucket;
	$wasabi_endpoint = 'https://s3.'.$wasabi_default_region.'.wasabisys.com';
	$raw_credentials = array(
									'credentials' => [
										'key' => $wasabi_access_key_id,
										'secret' => $wasabi_secret_access_key
									],
									'endpoint' => $wasabi_endpoint, 
									'region' => $wasabi_default_region, 
									'version' => 'latest',
									'use_path_style_endpoint' => true
								);
	$s3 = S3Client::factory($raw_credentials);
	/* wasabi */
	    $image = DB::table('items_images')->where('itm_id', '=', $token)->first();
        $file= $image->item_image;
	    if($settings->site_s3_storage == 1)
		{
		   Storage::disk('s3')->delete($file);
		   
		}
		else if($settings->site_s3_storage == 2)
		{
		   $s3->deleteObject(['Bucket' => $wasabi_bucket, 'Key' => $file]);
		   
		  
		}
		else if($settings->site_s3_storage == 3)
		{
		   Storage::disk('dropbox')->delete($file);
		   
		}
		else if($settings->site_s3_storage == 4)
		{
		   Storage::disk('google')->delete($file);
		   
		}
		else
		{
	       $filename = public_path().'/storage/items/'.$file;
           File::delete($filename);
		}   
	
	    DB::table('items_images')->where('itm_id', '=', $token)->delete();	
	
	
  }
  
  
  public static function droThumb($item_token)
  {
     $image = DB::table('items')->where('item_token', $item_token)->first();
        $file= $image->item_thumbnail;
        $filename = public_path().'/storage/items/'.$file;
        File::delete($filename);
  }
  
  
  public static function droPreview($item_token)
  {
     $image = DB::table('items')->where('item_token', $item_token)->first();
        $file= $image->item_preview;
        $filename = public_path().'/storage/items/'.$file;
        File::delete($filename);
  }
  
  
  public static function droFile($item_token)
  {
     $image = DB::table('items')->where('item_token', $item_token)->first();
        $file= $image->item_file;
        $filename = public_path().'/storage/items/'.$file;
        File::delete($filename);
  }
  
  public static function drovideoFile($item_token)
  {
     $image = DB::table('items')->where('item_token', $item_token)->first();
        $file= $image->video_file;
        $filename = public_path().'/storage/items/'.$file;
        File::delete($filename);
  }
  
  public static function saveitemImages($imgdata)
  {
   
      DB::table('items_images')->insert($imgdata);
     
 
  }
  
  
  public static function forceDeleted($token)
  {
  
	  
        $settings = DB::table('settings')->where('sid', 1)->first();
		/* wasabi */
	$wasabi_access_key_id = $settings->wasabi_access_key_id;
	$wasabi_secret_access_key = $settings->wasabi_secret_access_key;
	$wasabi_default_region = $settings->wasabi_default_region;
	$wasabi_bucket = $settings->wasabi_bucket;
	$wasabi_endpoint = 'https://s3.'.$wasabi_default_region.'.wasabisys.com';
	$raw_credentials = array(
									'credentials' => [
										'key' => $wasabi_access_key_id,
										'secret' => $wasabi_secret_access_key
									],
									'endpoint' => $wasabi_endpoint, 
									'region' => $wasabi_default_region, 
									'version' => 'latest',
									'use_path_style_endpoint' => true
								);
	$s3 = S3Client::factory($raw_credentials);
	/* wasabi */
	    $image = DB::table('items')->where('item_token', $token)->first();
        $file= $image->item_thumbnail;
        $file_2= $image->item_preview;
		$getall = DB::table('items_images')->where('item_token', $token)->get();
		
		if($settings->site_s3_storage == 1)
		{
		   Storage::disk('s3')->delete($file);
		   Storage::disk('s3')->delete($file_2);
		   
		   foreach($getall as $get)
			{
				$file_main= $get->item_image;
				Storage::disk('s3')->delete($file_main);
			}
		   
		   Storage::disk('s3')->delete($image->item_file);
		   Storage::disk('s3')->delete($image->video_file);
		   Storage::disk('s3')->delete($image->audio_file);
		   
		   
		   
		}
		else if($settings->site_s3_storage == 2)
		{
		    $s3->deleteObject(['Bucket' => $wasabi_bucket, 'Key' => $file]);
			 $s3->deleteObject(['Bucket' => $wasabi_bucket, 'Key' => $file_2]);
		   foreach($getall as $get)
			{
				$file_main= $get->item_image;
				
				$s3->deleteObject(['Bucket' => $wasabi_bucket, 'Key' => $file_main]);
			}
			$s3->deleteObject(['Bucket' => $wasabi_bucket, 'Key' => $image->item_file]);
			$s3->deleteObject(['Bucket' => $wasabi_bucket, 'Key' => $image->video_file]);
			$s3->deleteObject(['Bucket' => $wasabi_bucket, 'Key' => $image->audio_file]);
		   
		}
		else if($settings->site_s3_storage == 3)
		{
		   Storage::disk('dropbox')->delete($file);
		   Storage::disk('dropbox')->delete($file_2);
		   foreach($getall as $get)
			{
				$file_main= $get->item_image;
				Storage::disk('dropbox')->delete($file_main);
			}
		   Storage::disk('dropbox')->delete($image->item_file);
		   Storage::disk('dropbox')->delete($image->video_file);
		   Storage::disk('dropbox')->delete($image->audio_file);
		}
		else if($settings->site_s3_storage == 4)
		{
		   Storage::disk('google')->delete($file);
		   Storage::disk('google')->delete($file_2);
		   foreach($getall as $get)
			{
				$file_main= $get->item_image;
				Storage::disk('google')->delete($file_main);
			}
		   Storage::disk('google')->delete($image->item_file);
		   Storage::disk('google')->delete($image->video_file);
		   Storage::disk('google')->delete($image->audio_file);
		}
		else
		{
		    
			$filename = public_path().'/storage/items/'.$file;
		    File::delete($filename);
            $filename_2 = public_path().'/storage/items/'.$file_2;
		    File::delete($filename_2);
			
			foreach($getall as $get)
			{
				$file_main= $get->item_image;
				$filename_main = public_path().'/storage/items/'.$file_main;
				File::delete($filename_main);
			}
			
			$file_main= $image->item_file;
			$filename_main = public_path().'/storage/items/'.$file_main;
			File::delete($filename_main);
			
			$vid_file_main= $image->video_file;
			$vid_filename_main = public_path().'/storage/items/'.$vid_file_main;
			File::delete($vid_filename_main);
			
			$vid_file_main_tt= $image->audio_file;
			$vid_filename_main_tt = public_path().'/storage/items/'.$vid_file_main_tt;
			File::delete($vid_filename_main_tt);
			
				
			
			
			
		}
		
		
			  
	 DB::table('items')->where('item_token', '=', $token)->delete(); 
	
  }
  
  
  
  
  public static function admindeleteData($token,$data)
  {
  DB::table('items')
      ->where('item_token', $token)
      ->update($data);
	  
     
	
  }
  
  public static function deleteData($token,$data){
        $settings = DB::table('settings')->where('sid', 1)->first();
		/* wasabi */
	$wasabi_access_key_id = $settings->wasabi_access_key_id;
	$wasabi_secret_access_key = $settings->wasabi_secret_access_key;
	$wasabi_default_region = $settings->wasabi_default_region;
	$wasabi_bucket = $settings->wasabi_bucket;
	$wasabi_endpoint = 'https://s3.'.$wasabi_default_region.'.wasabisys.com';
	$raw_credentials = array(
									'credentials' => [
										'key' => $wasabi_access_key_id,
										'secret' => $wasabi_secret_access_key
									],
									'endpoint' => $wasabi_endpoint, 
									'region' => $wasabi_default_region, 
									'version' => 'latest',
									'use_path_style_endpoint' => true
								);
	$s3 = S3Client::factory($raw_credentials);
	/* wasabi */
	    $image = DB::table('items')->where('item_token', $token)->first();
        $file= $image->item_thumbnail;
        $filename = public_path().'/storage/items/'.$file;
		File::delete($filename);
		
		
        $file_2= $image->item_preview;
        $filename_2 = public_path().'/storage/items/'.$file_2;
		File::delete($filename_2);
		
		if($settings->site_s3_storage == 1)
		{
		   Storage::disk('s3')->delete($image->item_file);
		   Storage::disk('s3')->delete($image->video_file);
		}
		else if($settings->site_s3_storage == 2)
		{
		   $s3->deleteObject(['Bucket' => $wasabi_bucket, 'Key' => $image->item_file]);
		   $s3->deleteObject(['Bucket' => $wasabi_bucket, 'Key' => $image->video_file]);
		   
		   
		}
		else
		{
			$file_main= $image->item_file;
			$filename_main = public_path().'/storage/items/'.$file_main;
			File::delete($filename_main);
			
			$vid_file_main= $image->video_file;
			$vid_filename_main = public_path().'/storage/items/'.$vid_file_main;
			File::delete($vid_filename_main);
		}
		
	$getall = DB::table('items_images')->where('item_token', $token)->get();	
	foreach($getall as $get)
	{
	    $file_main= $get->item_image;
        $filename_main = public_path().'/storage/items/'.$file_main;
        File::delete($filename_main);
	}	
		
	
	DB::table('items')
      ->where('item_token', $token)
      ->update($data);
	
  }
  
  
  public static function edititemData($token)
  {
    $value = DB::table('items')
      ->where('item_token', $token)
      ->first();
	return $value;
  }
  
  
  
  public static function getuseritemCount($user_id)
  {
    
    $get=DB::table('items')->where('user_id','=',$user_id)->where('item_status','=',1)->where('drop_status','=','no')->orderBy('item_id', 'desc')->get(); 
	$value = $get->count();
    return $value;
	
  }	
  
   public static function getfollowuserCheck($user_id)
  {
    $login_id = Auth::user()->id;
    $get=DB::table('follow')->where('follower_user_id','=',$login_id)->where('following_user_id','=',$user_id)->get(); 
	$value = $get->count();
    return $value;
	
  }	
  
  public static function checkDels($token)
  {
    
    $get=DB::table('items')->where('item_token','=',$token)->where('drop_status','=','no')->get(); 
	$value = $get->count();
    return $value;
	
  }	
  
  
  
  
  public static function getfollowingCount($user_id)
  {
    
    $get=DB::table('follow')->where('follower_user_id','=',$user_id)->get(); 
	$value = $get->count();
    return $value;
	
  }	
  
  
  public static function getfollowerCount($user_id)
  {
    
    $get=DB::table('follow')->where('following_user_id','=',$user_id)->get(); 
	$value = $get->count();
    return $value;
	
  }	
  
  public static function getfollowerView($user_id)
  {
    
    $value=DB::table('users')->join('follow','users.id','follow.follower_user_id')->leftJoin('country','users.country','country.country_id')->where('follow.following_user_id','=',$user_id)->get(); 
	return $value;
	
  }
  
  public static function getfollowingView($user_id)
  {
    
    $value=DB::table('users')->join('follow','users.id','follow.following_user_id')->leftJoin('country','users.country','country.country_id')->where('follow.follower_user_id','=',$user_id)->get(); 
	return $value;
	
  }
  
  
  public static function getsaleitemCount($user_id)
  {
    
    $get=DB::table('item_order')->where('item_user_id','=',$user_id)->where('order_status','=','completed')->where('approval_status','=','payment released to vendor')->orderBy('ord_id', 'desc')->get(); 
	$value = $get->count();
    return $value;
	
  }	
  
  
  
  public static function getimagesCount($token)
  {

    $get=DB::table('items_images')->where('item_token','=', $token)->orderBy('itm_id', 'desc')->get();
	$value = $get->count(); 
    return $value;
	
  }
  
  
  public static function getimagesData($token)
  {

    $value=DB::table('items_images')->where('item_token','=', $token)->orderBy('itm_id', 'desc')->get(); 
    return $value;
	
  }
	
	
  public static function getsingleimagesData($token)
  {

    $value=DB::table('items_images')->where('item_token','=', $token)->orderBy('itm_id', 'desc')->first(); 
    return $value;
	
  }
 
  
  /* items */
  
  
  /* shop */
  
  public static function allitemData()
  {
    
    $value=DB::table('items')->join('users','users.id','items.user_id')->where('items.item_status','=',1)->where('items.drop_status','=','no')->orderBy('items.item_id', 'asc')->get(); 
    return $value;
	
  }
  
  
  public static function getitemcatData()
  {

    $value=DB::table('category')->where('drop_status','=','no')->where('category_status','=',1)->orderBy('cat_id', 'asc')->get(); 
    return $value;
	
  }
  
   public static function getgroupitemData()
  {

    $value=DB::table('items')->where('item_status','=',1)->where('drop_status','=','no')->where('item_category_type','=','category')->orderBy('item_id', 'desc')->get()->groupBy('item_category'); 
    return $value;
	
  }	
  
  
  
  public static function minpriceData()
  {

    $value=DB::table('items')->where('item_status','=',1)->where('drop_status','=','no')->orderBy('regular_price', 'asc')->first(); 
    return $value;
	
  }	
  
  public static function maxpriceData()
  {

    $value=DB::table('items')->where('item_status','=',1)->where('drop_status','=','no')->orderBy('extended_price', 'desc')->first(); 
    return $value;
	
  }
  
  
  public static function minpriceCount()
  {

    $get=DB::table('items')->where('item_status','=',1)->where('drop_status','=','no')->orderBy('regular_price', 'asc')->get();
	$value = $get->count();  
    return $value;
	
  }	
  
  public static function maxpriceCount()
  {

    $get=DB::table('items')->where('item_status','=',1)->where('drop_status','=','no')->orderBy('extended_price', 'desc')->get();
	$value = $get->count();  
    return $value;
	
  }
  
  
  
  
  public static function recentitemData()
  {
    
    $value=DB::table('items')->join('users','users.id','items.user_id')->where('items.item_status','=',1)->where('items.drop_status','=','no')->orderBy('items.item_id', 'desc')->get(); 
    return $value;
	
  }
  
  
  public static function featureditemData()
  {
    
    $value=DB::table('items')->join('users','users.id','items.user_id')->where('items.item_status','=',1)->where('items.drop_status','=','no')->where('items.item_featured','=','yes')->orderBy('items.item_id', 'desc')->get(); 
    return $value;
	
  }
  
  public static function freeitemData()
  {
    
    $value=DB::table('items')->join('users','users.id','items.user_id')->where('items.item_status','=',1)->where('items.drop_status','=','no')->where('items.free_download','=',1)->orderBy('items.item_id', 'desc')->get(); 
    return $value;
	
  }
  
  
   public static function categoryitemData($type,$id)
  {
    
    $value=DB::table('items')->join('users','users.id','items.user_id')->where('items.item_status','=',1)->where('items.drop_status','=','no')->where('items.item_category','=',$id)->where('items.item_category_type','=',$type)->orderBy('items.item_id', 'desc')->get(); 
    return $value;
	
  }
  
  
  
  public static function searchcatData($cat_id,$cat_name)
  {
    
    $value=DB::table('items')->join('users','users.id','items.user_id')->where('items.item_status','=',1)->where('items.drop_status','=','no')->where('items.item_category','=',$cat_id)->where('items.item_category_type','=',$cat_name)->orderBy('items.item_id', 'desc')->get(); 
    return $value;
	
  }
  
  
  
  
  
  public static function searchtextData($product_item)
  {
    
    $value=DB::table('items')->join('users','users.id','items.user_id')->where('items.item_status','=',1)->where('items.drop_status','=','no')->where('items.item_name', 'LIKE', "%$product_item%")->orderBy('items.item_id', 'desc')->get(); 
    return $value;
	
  }
  
  
  
  public static function searchbothData($product_item,$cat_id,$cat_name)
  {
    
    $value=DB::table('items')->join('users','users.id','items.user_id')->where('items.item_status','=',1)->where('items.drop_status','=','no')->where('items.item_name', 'LIKE', "%$product_item%")->where('items.item_category','=',$cat_id)->where('items.item_category_type','=',$cat_name)->orderBy('items.item_id', 'desc')->get(); 
    return $value;
	
  }
  
  
  
  public static function itemtypeData($slug)
  {
    
    $value=DB::table('items')->join('users','users.id','items.user_id')->where('items.item_status','=',1)->where('items.drop_status','=','no')->where('items.item_type','=',$slug)->orderBy('items.item_id', 'desc')->get(); 
    return $value;
	
  }
  /* shop */
  
  
  
  /* item */
  
  
  public static function singleitemData($item_slug)
  {
    $today = date("Y-m-d");
    $additional['settings'] = Settings::editAdditional();
	if($additional['settings']->subscription_mode == 1)
	{
    $value=DB::table('items')->join('users','users.id','items.user_id')->where('users.user_subscr_date','>=',$today)->where('items.item_status','=',1)->where('items.drop_status','=','no')->where('items.item_slug','=',$item_slug)->first(); 
	}
	else
	{
	$value=DB::table('items')->join('users','users.id','items.user_id')->where('items.item_status','=',1)->where('items.drop_status','=','no')->where('items.item_slug','=',$item_slug)->first(); 
	}
    return $value;
	
  }	
  
  
  public static function singleitemCount($item_slug)
  {
    $today = date("Y-m-d");
    $additional['settings'] = Settings::editAdditional();
	if($additional['settings']->subscription_mode == 1)
	{
    $get=DB::table('items')->join('users','users.id','items.user_id')->where('users.user_subscr_date','>=',$today)->where('items.item_status','=',1)->where('items.drop_status','=','no')->where('items.item_slug','=',$item_slug)->get(); 
	}
	else
	{
	$get=DB::table('items')->join('users','users.id','items.user_id')->where('items.item_status','=',1)->where('items.drop_status','=','no')->where('items.item_slug','=',$item_slug)->get(); 
	}
    $value = $get->count();  
    return $value;
	
  }	
  
  
  
  
  
  public static function relateditemData($item_id,$item_user_id)
  {
    
    $value=DB::table('items')->join('users','users.id','items.user_id')->where('items.item_status','=',1)->where('items.drop_status','=','no')->where('items.user_id','=',$item_user_id)->where('items.item_id','!=',$item_id)->orderBy('items.item_id', 'asc')->take(3)->get(); 
    return $value;
	
  }
  
  
  
  public static function AgainData($item_slug)
  {
    $today = date("Y-m-d");
    $additional['settings'] = Settings::editAdditional();
	if($additional['settings']->subscription_mode == 1)
	{
    $get=DB::table('items')->join('users','users.id','items.user_id')->where('users.user_subscr_date','>=',$today)->where('items.item_status','=',1)->where('items.drop_status','=','no')->where('items.item_slug','=',$item_slug)->get(); 
	}
	else
	{
	$get=DB::table('items')->join('users','users.id','items.user_id')->where('items.item_status','=',1)->where('items.drop_status','=','no')->where('items.item_slug','=',$item_slug)->get(); 
	}
	$value = $get->count();
    return $value;
	
  }	
  
  
  public static function getfavouriteCount($item_id,$log_user)
  {

    $get=DB::table('items_favorite')->where('item_id','=', $item_id)->where('user_id','=', $log_user)->get();
	$value = $get->count(); 
    return $value;
	
  }
  
  
  public static function savefavouriteData($data)
  {
   
      DB::table('items_favorite')->insert($data);
     
 
  }
  
  public static function updatefavouriteData($item_id,$record)
  {
    DB::table('items')
      ->where('item_id', $item_id)
      ->update($record);
  }
  
  
  public static function ifpurchaseCount($token)
  {
    $today = date('Y-m-d');
	$user_id = Auth::user()->id;
    $get=DB::table('item_order')->where('item_token','=', $token)->where('user_id','=', $user_id)->where('order_status','=','completed')->where('end_date','>',$today)->get();
	$value = $get->count(); 
    return $value;
	
  }
  
  
  /* item */
  
  
  
  /* favourite */
  
  public static function getfavitemData()
  {
    $user_id = Auth::user()->id;
    $value=DB::table('items')->join('users','users.id','items.user_id')->join('items_favorite','items_favorite.item_id','items.item_id')->where('items_favorite.user_id','=',$user_id)->where('items.item_status','=',1)->where('items.drop_status','=','no')->orderBy('items.item_id', 'desc')->get(); 
    return $value;
	
  }
  
  
  public static function dropFavitem($fav_id){
    
		
	DB::table('items_favorite')->where('fav_id', '=', $fav_id)->delete();	
	
	
  }
  
  
  public static function deleteEntire($order_id){
    
		
	DB::table('item_order')->where('purchase_token', '=', $order_id)->delete();	
	DB::table('item_checkout')->where('purchase_token', '=', $order_id)->delete();	
	
  }
  
  
  public static function deleteRefund($refund_id){
    
		
	DB::table('item_refund')->where('refund_id', '=', $refund_id)->delete();	
	
	
  }
  
  
   public static function deleteWithdraw($wd_id){
    
		
	DB::table('item_withdrawal')->where('wd_id', '=', $wd_id)->delete();	
	
	
  }
  
  
  public static function selecteditemData($item_id)
  {

    $value=DB::table('items')->where('item_id','=',$item_id)->first(); 
    return $value;
	
  }	
	
  /* favourite */
  
  
  
  /* tags */
  
  public static function itemtagData($slug)
  {
    $nslug = str_replace("-"," ",$slug); 
    $value=DB::table('items')->join('users','users.id','items.user_id')->where('items.item_status','=',1)->where('items.drop_status','=','no')->where('items.item_tags', 'LIKE', "%$nslug%")->orderBy('items.item_id', 'desc')->get(); 
    return $value;
	
  }
  
  /* tags */
  
  
  
  /* cart */
  
  public static function getorderCount($item_id,$session_id,$order_status)
  {

    $get=DB::table('item_order')->where('item_id','=', $item_id)->where('session_id','=', $session_id)->where('order_status','=', $order_status)->get();
	$value = $get->count(); 
    return $value;
	
  }
  
  
  public static function savecartData($savedata)
  {
   
      DB::table('item_order')->insert($savedata);
     
 
  }
  
  
  public static function updatecartData($item_id,$session_id,$order_status,$updatedata)
  {
    DB::table('item_order')
      ->where('session_id', $session_id)
	  ->where('item_id', $item_id)
	  ->where('order_status', $order_status)
      ->update($updatedata);
  }
  
  
   public static function getcartData()
  {
    
	$session_id = Session::getId();
    $value=DB::table('item_order')->join('users','users.id','item_order.item_user_id')->join('items','items.item_id','item_order.item_id')->where('item_order.session_id','=',$session_id)->where('items.item_status','=',1)->where('items.drop_status','=','no')->where('item_order.order_status','=','pending')->orderBy('item_order.ord_id', 'desc')->get(); 
    return $value;
	
  }
  
  public static function getcartCount()
  {
    $session_id = Session::getId();
    $get=DB::table('item_order')->join('users','users.id','item_order.item_user_id')->join('items','items.item_id','item_order.item_id')->where('item_order.session_id','=',$session_id)->where('items.item_status','=',1)->where('items.drop_status','=','no')->where('item_order.order_status','=','pending')->orderBy('item_order.ord_id', 'desc')->get(); 
	$value = $get->count();
    return $value;
	
  }
  
  
  public static function deletecartdata($ord_id){
    
	
	
	DB::table('item_order')->where('ord_id', '=', $ord_id)->where('order_status','=','pending')->delete();	
	
	
  }
  
  public static function deletecartempty($session_id){
    
	
	
	DB::table('item_order')->where('session_id', '=', $session_id)->where('order_status','=','pending')->delete();	
	
	
  }
  
  /* cart */
	
	
	
  /* checkout */
  
  public static function getcheckoutCount($purchase_token,$user_id,$payment_status)
  {

    $get=DB::table('item_checkout')->where('purchase_token','=', $purchase_token)->where('user_id','=', $user_id)->where('payment_status','=', $payment_status)->get();
	$value = $get->count(); 
    return $value;
	
  }
  
  
  public static function savecheckoutData($savedata)
  {
   
      DB::table('item_checkout')->insert($savedata);
     
 
  }
  
  
  public static function updatecheckoutData($purchase_token,$user_id,$payment_status,$updatedata)
  {
    DB::table('item_checkout')
      ->where('purchase_token', $purchase_token)
	  ->where('user_id', $user_id)
	  ->where('payment_status', $payment_status)
      ->update($updatedata);
  }
  
  
  public static function singleorderupData($order,$orderdata)
  {
    DB::table('item_order')
      ->where('ord_id', $order)
	  ->update($orderdata);
  }
  public static function singleSession($session_id)
  {
    $value = DB::table('item_order')
      ->where('session_id', $session_id)
	  ->where('order_status', 'pending')
	  ->first();
	return $value;
  }
  
  public static function SessionCheck($session_id)
  {

    $get=DB::table('item_order')->where('session_id', $session_id)->where('order_status', 'pending')->get();
	$value = $get->count(); 
    return $value;
	
  }
  
  
  public static function singleorderData($order)
  {
    $value = DB::table('item_order')
      ->where('ord_id', $order)
      ->first();
	return $value;
  }
  public static function singleorderToken($purchase_token)
  {
    $value = DB::table('item_order')
      ->where('purchase_token', $purchase_token)
      ->first();
	return $value;
  }
  
   public static function singleordupdateData($purchased_token,$orderdata)
  {
    DB::table('item_order')
      ->where('purchase_token', $purchased_token)
	  ->update($orderdata);
  }
  
  
  public static function singlecheckoutData($purchased_token,$checkoutdata)
  {
    DB::table('item_checkout')
      ->where('purchase_token', $purchased_token)
	  ->update($checkoutdata);
  }
  
  
  
  
  public static function solditemData($token)
  {

    $value=DB::table('items')->where('item_token','=',$token)->first(); 
    return $value;
	
  }
  
  public static function possibleVerify($purchase_code) 
  {

    $get=DB::table('item_order')->join('users','users.id','item_order.user_id')->join('items','items.item_id','item_order.item_id')->where('item_order.order_status','=', 'completed')->where('item_order.approval_status','=', 'payment released to vendor')->where('item_order.purchase_code','=', $purchase_code)->first();
	return $get;
	
  }
  
  
  public static function checkVerify($purchase_code) 
  {

    $get=DB::table('item_order')->join('users','users.id','item_order.user_id')->join('items','items.item_id','item_order.item_id')->where('item_order.order_status','=', 'completed')->where('item_order.approval_status','=', 'payment released to vendor')->where('item_order.purchase_code','=', $purchase_code)->get();
	$value = $get->count();
	return $value;
	
  }
  
  
  public static function checkSold($token,$user_id)
  {

    $get=DB::table('item_order')->join('users','users.id','item_order.user_id')->join('items','items.item_id','item_order.item_id')->where('item_order.order_status','=', 'completed')->where('item_order.approval_status','!=', 'payment released to buyer')->where('items.item_token','=', $token)->where('item_order.user_id','=', $user_id)->get();
	$value = $get->count(); 
    return $value;
	
  }
  
  
  public static function getcheckoutData($token)
  {

    $value=DB::table('item_checkout')->where('purchase_token','=',$token)->first(); 
    return $value;
	
  }
  
  
  public static function getorderData($order)
  {

    $value=DB::table('item_order')->where('ord_id','=',$order)->first(); 
    return $value;
	
  }
  
  
  public static function selleritemData($token,$logged_id)
  {
    
    $value=DB::table('items')->where('user_id','=',$logged_id)->where('item_token','=',$token)->first(); 
    return $value;
	
  }
  
  public static function checkInSeller($token,$logged_id)
  {

    $get=DB::table('items')->where('user_id','=',$logged_id)->where('item_token','=',$token)->get();
	$value = $get->count(); 
    return $value;
	
  }
  
  
  public static function lastorderData()
  {
    $session_id = Session::getId();
    $value=DB::table('item_order')->where('session_id','=',$session_id)->where('order_status','=','pending')->orderBy('ord_id', 'desc')->first(); 
    return $value;
	
  }
  
  public static function deletecartremove($session_id)
  {
    
	
	
	DB::table('item_order')->where('session_id', '=', $session_id)->where('order_status','=','pending')->delete();	
	
	
  }
  /* checkout */	
	
	
  /* purchases */
  
  public static function getuserOrders()
  {
    $user_id = Auth::user()->id;
    $value=DB::table('items')->join('users','users.id','items.user_id')->join('item_order','items.item_id','item_order.item_id')->leftjoin('item_ratings', 'item_ratings.order_id', '=', 'item_order.ord_id')->where('item_order.user_id','=',$user_id)->where('item_order.order_status','=','completed')->orderBy('item_order.ord_id', 'desc')->get(); 
    return $value;
	
  }
  
  
  public static function checkRating($item_token,$user_id)
  {

    $get=DB::table('item_ratings')->where('or_item_token','=', $item_token)->where('or_user_id','=', $user_id)->get();
	$value = $get->count(); 
    return $value;
	
  }
  
  
  public static function saveRating($savedata)
  {
   
      DB::table('item_ratings')->insert($savedata);
     
 
  }
  
  
  public static function updateRating($item_token,$user_id,$updata)
  {
    DB::table('item_ratings')
      ->where('or_item_token', $item_token)
	  ->where('or_user_id', $user_id)
      ->update($updata);
  }
  
  /* purchases */
  
  
  
  /* sales */
  
  public static function getuserCheckout()
  {
    $user_id = Auth::user()->id;
    $value=DB::table('item_checkout')->whereRaw("find_in_set('".$user_id."',item_user_id)")->where('payment_status','=','completed')->orderBy('chout_id', 'desc')->get(); 
    return $value;
	
  }
  
  
  
  public static function getpurchaseCheckout()
  {
    $user_id = Auth::user()->id;
    $value=DB::table('item_checkout')->where('user_id','=',$user_id)->where('payment_status','=','completed')->orderBy('chout_id', 'desc')->get(); 
    return $value;
	
  }
  
  
  public static function getcreditOrder()
  {
    $user_id = Auth::user()->id;
    $value=DB::table('item_order')->where('item_user_id','=',$user_id)->where('order_status','=','completed')->where('approval_status','=','payment released to vendor')->orderBy('ord_id', 'desc')->get(); 
    return $value;
	
  }
  
  
  
  public static function singlecheckoutView($token)
  {
    $value = DB::table('item_checkout')
      ->where('purchase_token', $token)
      ->first();
	return $value;
  }
  
  
  public static function getorderView($token)
  {
    $value = DB::table('item_order')->join('users','users.id','item_order.user_id')->join('items','items.item_id','item_order.item_id')->where('purchase_token', $token)->get();
	return $value;
  }
  
  /* sales */
  
  
  /* refund */
  
  public static function checkRefund($item_token,$user_id)
  {

    $get=DB::table('item_refund')->where('ref_item_token','=', $item_token)->where('ref_user_id','=', $user_id)->where('ref_refund_approval','=', 'accepted')->get();
	$value = $get->count(); 
    return $value;
	
  }
  
  public static function saveRefund($savedata)
  {
   
      DB::table('item_refund')->insert($savedata);
     
 
  }
  
  
  
  /* refund */
  
  
  /* review */
  
  
  public static function getreviewData($user_id)
  {

    $get=DB::table('item_ratings')->where('or_item_user_id','=', $user_id)->get();
	$value = $get->count(); 
    return $value;
	
  }
  
  
  public static function getreviewRecord($user_id)
  {
    $value = DB::table('item_ratings')->where('or_item_user_id','=', $user_id)->get();
	return $value;
  }
  
  
  
  public static function getreviewCount($item_id)
  {

    $get=DB::table('item_ratings')->where('or_item_id','=', $item_id)->get();
	$value = $get->count(); 
    return $value;
	
  }
  
  
  public static function getreviewView($item_id)
  {
    $value = DB::table('item_ratings')->where('or_item_id','=', $item_id)->get();
	return $value;
  }
  
  
  
  
  
  public static function getreviewUser($user_id)
  {
    $value = DB::table('item_ratings')->join('users','users.id','item_ratings.or_user_id')->join('items','items.item_id','item_ratings.or_item_id')->where('or_item_user_id','=', $user_id)->orderBy('item_ratings.rating_date', 'desc')->get();
	return $value;
  }
  
  
  public static function getreviewCountUser($user_id)
  {
    $get = DB::table('item_ratings')->join('users','users.id','item_ratings.or_user_id')->join('items','items.item_id','item_ratings.or_item_id')->where('or_item_user_id','=', $user_id)->orderBy('item_ratings.rating_date', 'desc')->get();
	$value = $get->count(); 
	return $value;
  }
  
  /* review */
  
  
  /* admin orders */
  
  public static function adminorderItem($token)
  {
    
    $value=DB::table('item_order')->join('users','users.id','item_order.item_user_id')->where('item_order.purchase_token','=',$token)->where('item_order.order_status','=','completed')->orderBy('item_order.ord_id', 'desc')->get(); 
    return $value;
	
  }	
  
  
  public static function getsingleOrder($token)
  {
    
    $value=DB::table('item_checkout')->join('users','users.id','item_checkout.user_id')->where('item_checkout.purchase_token','=',$token)->where('item_checkout.payment_status','=','completed')->orderBy('item_checkout.chout_id', 'desc')->first(); 
    return $value;
	
  }
  
  
  	
  /* admin orders */
  
  
  
  /* admin refund */
  
   public static function getrefundItem()
  {
    
    $value=DB::table('item_refund')->join('users','users.id','item_refund.ref_user_id')->join('items','items.item_id','item_refund.ref_item_id')->orderBy('item_refund.refund_id', 'desc')->get(); 
    return $value;
	
  }
  
  
  public static function refundupData($refund_id,$refundata)
  {
    DB::table('item_refund')
      ->where('refund_id', $refund_id)
	  ->update($refundata);
  }
  /* admin refund */
  
  
  /* delete rating */
  
  public static function deleteRating($ord_id){
    
	
	
	DB::table('item_ratings')->where('order_id', '=', $ord_id)->delete();	
	
	
  }
  
  /* delete rating */
  
  
  /* rating */
  
  public static function getratingItem()
  {
    
    $value=DB::table('item_ratings')->join('users','users.id','item_ratings.or_user_id')->join('items','items.item_id','item_ratings.or_item_id')->orderBy('item_ratings.rating_id', 'desc')->get(); 
    return $value;
	
  }
  
  
  public static function singleratingItem($rating_id)
  {
    
    $value=DB::table('item_ratings')->join('users','users.id','item_ratings.or_user_id')->join('items','items.item_id','item_ratings.or_item_id')->where('item_ratings.rating_id', '=', $rating_id)->first();
    return $value;
	
  }
  
  public static function updateratingData($rating_id,$updata){
    DB::table('item_ratings')
      ->where('rating_id', $rating_id)
      ->update($updata);
  }
  
  public static function dropRating($rating_id){
    
	
	
	DB::table('item_ratings')->where('rating_id', '=', $rating_id)->delete();	
	
	
  }
  
  /* rating */
  
  
  /* withdrawal */
  
  public static function savedrawalData($data)
  {
   
      DB::table('item_withdrawal')->insert($data);
     
 
  }
  
  
  public static function getdrawalData()
  {
    $user_id = Auth::user()->id;
    $value=DB::table('item_withdrawal')->where('wd_user_id','=',$user_id)->orderBy('wd_id', 'desc')->get(); 
    return $value;
	
  }
  
  
  public static function getdrawalView()
  {
    $user_id = Auth::user()->id;
    $value=DB::table('item_withdrawal')->where('wd_user_id','=',$user_id)->where('wd_status','=','paid')->orderBy('wd_id', 'desc')->get(); 
    return $value;
	
  }
  
  /* withdrawal */
  
  
  /* admin withdrawal */
  
  public static function getwithdrawalData()
  {
    
    $value=DB::table('item_withdrawal')->join('users','users.id','item_withdrawal.wd_user_id')->orderBy('item_withdrawal.wd_id', 'desc')->get(); 
    return $value;
	
  }
  
  
  public static function updatedrawalData($wd_id,$user_id,$drawal_data)
  {
    DB::table('item_withdrawal')
      ->where('wd_id', $wd_id)
	  ->where('wd_user_id',$user_id)
      ->update($drawal_data);
  }
  
  public static function singledrawalData($wd_id)
  {
    $value = DB::table('item_withdrawal')
      ->where('wd_id', $wd_id)
      ->first();
	return $value;
  }
  
  /* admin withdrawal */
  
  
  /* follow */
  
  public static function saveFollow($data)
  {
   
      DB::table('follow')->insert($data);
     
 
  }
  
  public static function unFollow($my_id,$follow_id){
    
		
	DB::table('follow')->where('follower_user_id', '=', $my_id)->where('following_user_id', '=', $follow_id)->delete();	
	
	
  }
  
  /* follow */
  
  public static function getreviewItems($item_id)
  {
    $value = DB::table('item_ratings')->join('users','users.id','item_ratings.or_user_id')->join('items','items.item_id','item_ratings.or_item_id')->where('item_ratings.or_item_id','=', $item_id)->orderBy('item_ratings.rating_date', 'desc')->get();
	return $value;
  }
  
  
  /* follow */
  
  
  /* comment */
  
   public static function savecommentData($comment_data)
  {
   
      DB::table('item_comments')->insert($comment_data);
     
 
  }
  
  
  public static function replycommentData($comment_data)
  {
   
      DB::table('item_comment_reply')->insert($comment_data);
     
 
  }
  /* comment */
  
  
  public static function getorderStatus($item_id,$user_id)
  {

    $value = DB::table('item_order')->join('users','users.id','item_order.user_id')->where('item_order.item_id','=', $item_id)->where('item_order.item_user_id','=', $user_id)->where('item_order.order_status','=', 'completed')->where('item_order.approval_status','=', 'payment released to vendor')->get();
	
    return $value;
	
  }
  
  
  public static function getcontactCount($from_email)
  {
    
    $get=DB::table('contact')->where('from_email','=',$from_email)->get(); 
	$value = $get->count();
    return $value;
	
  }	
  
  public static function saveContact($record)
  {
   
      DB::table('contact')->insert($record);
     
 
  }
  
  
  /* homepage reviews */
  
  
  public static function CollectedAmount($item_user_id)
  {
    
    $get=DB::table('item_order')->where('order_status','=','completed')->where('approval_status','=','payment released to vendor')->where('user_id','=',$item_user_id)->get(); 
	$value = $get->count();
    return $value;
	
  }
  
  
  public static function SoldAmount($item_user_id)
  {
    
    $value=DB::table('item_order')->where('order_status','=','completed')->where('approval_status','=','payment released to vendor')->where('item_user_id','=',$item_user_id)->get(); 
	return $value;
	
  }
  
  
  public static function trendsCount($token)
  {
    $today = date('Y-m-d', strtotime('today - 30 days'));
    $get=DB::table('item_order')->where('order_status','=','completed')->where('start_date','>',$today)->where('item_token','=',$token)->get(); 
	$value = $get->count();
    return $value;
	
  }	
  
  
  public static function homereviewsData()
  {
    $value = DB::table('item_ratings')->join('users','users.id','item_ratings.or_user_id')->where('users.drop_status','=', 'no')->orderBy('item_ratings.rating', 'desc')->take(10)->get();
	return $value;
  }
  
  
  public static function totalsaleitemCount()
  {
    
    $get=DB::table('item_order')->where('order_status','=','completed')->orderBy('ord_id', 'desc')->get(); 
	$value = $get->count();
    return $value;
	
  }	
  
  
  public static function totalfileItems()
  {
    
    $get=DB::table('items')->where('drop_status','=','no')->where('item_status','=',1)->orderBy('item_id', 'desc')->get(); 
	$value = $get->count();
    return $value;
	
  }	
  
  
  public static function totalearningCount()
  {
    
    $value=DB::table('item_order')->where('order_status','=','completed')->orderBy('ord_id', 'desc')->get(); 
	
    return $value;
	
  }	
  
  
  
  public static function featuredItems()
  {
    
    $value=DB::table('items')->join('users','users.id','items.user_id')->where('items.drop_status','=','no')->where('items.item_featured','=','yes')->where('items.item_status','=',1)->orderBy('items.item_id', 'desc')->take(8)->get(); 
	
    return $value;
	
  }
  
  public static function freeItems()
  {
    
    $value=DB::table('items')->join('users','users.id','items.user_id')->where('items.drop_status','=','no')->where('items.free_download','=',1)->where('items.item_status','=',1)->orderBy('items.item_id', 'desc')->take(8)->get(); 
	
    return $value;
	
  }
  
  
  /* homepage reviews */
  
  
  /* order check */
  
  
  public static function orderdataCheck($check_date)
  {
    
    $get=DB::table('item_checkout')->where('payment_status','=','completed')->where('payment_date','=',$check_date)->get(); 
	$value = $get->count();
    return $value;
	
  }	
  
  
  
  public static function itemapprovedCheck($status)
  {
    
    $get=DB::table('items')->join('users','users.id','items.user_id')->where('items.drop_status','=','no')->where('items.item_status','=',$status)->get(); 
	$value = $get->count();
    return $value;
	
  }	
  
  
  public static function totalorderData()
  {
    
    $get=DB::table('item_checkout')->join('users','users.id','item_checkout.user_id')->orderBy('item_checkout.chout_id', 'desc')->get(); 
	$value = $get->count();
    return $value;
	
  }	
  
  
  public static function totalitemCheck()
  {
    
    $get=DB::table('items')->join('users','users.id','items.user_id')->where('items.drop_status','=','no')->get(); 
	$value = $get->count();
    return $value;
	
  }	
  
  
  
  public static function totalitemcommentCheck()
  {
    
    $get=DB::table('item_comments')->get(); 
	$value = $get->count();
    return $value;
	
  }	
  /* order check */
  
  
    /* coupon */
	
	public static function singleCoupon($coupon)
   {
    
    $value=DB::table('coupon')->where('coupon_code','=',$coupon)->where('coupon_status','=',1)->first(); 
    return $value;
	
   }
	
	
	public static function checkCoupon($coupon)
   {
    $today_date = date('Y-m-d h:i a');
    $get=DB::table('coupon')->where('coupon_start_date','<=',$today_date)->where('coupon_end_date','>=',$today_date)->where('coupon_code','=',$coupon)->where('coupon_status','=',1)->get(); 
    $value = $get->count(); 
    return $value;
	
   }
   
   public static function getCoupon($coupon,$session_id)
  {
    
    $value=DB::table('coupon')->join('item_order','item_order.item_user_id','coupon.user_id')->where('coupon.coupon_code','=',$coupon)->where('coupon.coupon_status','=',1)->where('item_order.order_status','=','pending')->where('item_order.session_id','=',$session_id)->get();
    return $value;
	
  }
  
  
  public static function updateCoupon($order_id,$data)
  {
    DB::table('item_order')
      ->where('ord_id', $order_id)
      ->update($data);
  }
  
  public static function removeCoupon($coupon,$session_id,$data)
  {
    DB::table('item_order')
      ->where('coupon_code', $coupon)
	  ->where('session_id', $session_id)
	  ->where('order_status', 'pending')
      ->update($data);
  }
  /* coupon */
  
  
  public static function viewItemtype($type_id)
  {
    $value = DB::table('item_type')
      ->where('item_type_id', $type_id)
      ->first();
	return $value;
  }
  
  public static function slugItemtype($type_id)
  {
    $value = DB::table('item_type')
      ->where('item_type_id', $type_id)
      ->first();
	return $value;
  }
  
  public static function editItemtype($item_type_id, $data){
    DB::table('item_type')
      ->where('item_type_id', $item_type_id)
      ->update($data);
  }
  
  
  public static function emptycheck()
  {
    
    $get=DB::table('items')->where('item_type_id','=',Null)->where('drop_status','=','no')->get(); 
	$value = $get->count();
    return $value;
	
  }
  
  public static function matchRecord()
  {
    
    $value=DB::table('items')->join('item_type','item_type.item_type_slug','items.item_type')->where('items.item_type_id','=',Null)->where('items.drop_status','=','no')->get();
    return $value;
	
  }
  
  public static function upModify($item_id,$data){
    DB::table('items')
      ->where('item_id', $item_id)
      ->update($data);
  }	
  
  
}

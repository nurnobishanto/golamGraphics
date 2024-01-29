<?php

namespace Fickrr\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Fickrr\Http\Controllers\Controller;
use Session;
use Fickrr\Models\Pages;
use Fickrr\Models\Settings;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use DataTables;

class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
		
    }
	public function index()
    {
        return view('admin.test');
    }

	
	public function getUsers(Request $request)
    {
    	$data = Pages::getpageData();
        return DataTables::of($data)
            ->addColumn('Actions', function($data) {
                return '<a href="" class="btn btn-success btn-sm">Edit</a>
                    <a href="" class="btn btn-info btn-sm">View</a>';
				//return '<a href='.\URL::route('users.edit', $data->id).' class="btn btn-success btn-sm">Edit</a>
//                    <a href='.\URL::route('users.show', $data->id).' class="btn btn-info btn-sm">View</a>';	
					
            })
            ->addColumn('Status', function($data) {
                if($data->page_status == '1'){
                    return '<label class="badge badge-success">Active</label>';
                }else{
                    return '<label class="badge badge-danger">Inactive</label>';
                }
            })
            ->rawColumns(['Actions','Status'])
            ->make(true);
    }
	
	
	public function seo_slug($string)
	{
	    
		$spaceRepl = "-";
		$string = str_replace("&", "and", $string);
		$string = preg_replace("/[^a-zA-Z0-9 _-]/", "", $string);
		$string = preg_replace("/".$spaceRepl."+/", "", $string);
		$string = strtolower($string);
		$string = preg_replace("/[ ]+/", " ", $string);
		$string = str_replace(" ", $spaceRepl, $string);
		return $string;	
    
	}
	
	public function non_seo_slug($string)
	{
	    $spaceRepl = "-";
		$string = preg_replace("/[ ]+/", " ", $string);
        $string = str_replace(" ", $spaceRepl, $string);
        return $string;	
    
	}
	
	
	public function pages()
    {
        
		
		$pageData['pages'] = Pages::getpageData();
		return view('admin.pages',[ 'pageData' => $pageData]);
    }
    
	
	public function add_page()
	{
	   
	   return view('admin.add-page');
	}
	
	
	public function save_page(Request $request)
	{
 
    
         $page_title = $request->input('page_title');
		 $page_desc = htmlentities($request->input('page_desc'));
		 $additional['settings'] = Settings::editAdditional();
		 if($additional['settings']->site_url_rewrite == 1)
		 {
		   $page_slug = $this->seo_slug($page_title);
		 }
		 else
		 {
		   $page_slug = $this->non_seo_slug($page_title);
		 }
         
		 $page_status = $request->input('page_status');
		 $main_menu = $request->input('main_menu');
		 $footer_menu = $request->input('footer_menu');
		 if($request->input('menu_order'))
		 {
		    $menu_order = $request->input('menu_order');
		 }
		 else
		 {
		   $menu_order = 0;
		 }
		
		 $page_allow_seo = $request->input('page_allow_seo');
		 if($request->input('page_seo_keyword') != "")
		 {
		 $page_seo_keyword = $request->input('page_seo_keyword');
		 }
		 else
		 {
		 $page_seo_keyword = "";
		 }
		 if($request->input('page_seo_desc') != "")
		 {
		 $page_seo_desc = $request->input('page_seo_desc');
		 }
		 else
		 {
		 $page_seo_desc = "";
		 }
		 
		 
         
		 $request->validate([
							'page_title' => 'required',
							'page_desc' => 'required',
							'page_status' => 'required',
							
         ]);
		 $rules = array(
				
				
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
		
		
		 
		$data = array('page_title' => $page_title, 'page_desc' => $page_desc, 'page_slug' => $page_slug, 'page_status' => $page_status, 'main_menu' => $main_menu, 'footer_menu' => $footer_menu, 'menu_order' => $menu_order, 'page_allow_seo' => $page_allow_seo, 'page_seo_keyword' => $page_seo_keyword, 'page_seo_desc' => $page_seo_desc);
        Pages::insertpageData($data);
		return redirect('/admin/pages')->with('success', 'Insert successfully.');
        
            
 
       } 
     
    
  }
  
  
  
  public function delete_pages($page_id){

      
	  
      Pages::deletePagedata($page_id);
	  
	  return redirect()->back()->with('success', 'Delete successfully.');

    
  }
  
  
  public function edit_page($page_id)
	{
	   
	   $edit['page'] = Pages::editpageID($page_id);
	   return view('admin.edit-page', [ 'edit' => $edit, 'page_id' => $page_id]);
	}
	
	
	
	public function update_page(Request $request)
	{
	
	   $page_title = $request->input('page_title');
		 $page_desc = htmlentities($request->input('page_desc'));
		 $additional['settings'] = Settings::editAdditional();
		 if($additional['settings']->site_url_rewrite == 1)
		 {
		   $page_slug = $this->seo_slug($page_title);
		 }
		 else
		 {
		   $page_slug = $this->non_seo_slug($page_title);
		 }
         
		 $page_status = $request->input('page_status');
		 
		 $page_id = $request->input('page_id');
		 $main_menu = $request->input('main_menu');
		 $footer_menu = $request->input('footer_menu');
		 if($request->input('menu_order'))
		 {
		    $menu_order = $request->input('menu_order');
		 }
		 else
		 {
		   $menu_order = 0;
		 }
		 
         $page_allow_seo = $request->input('page_allow_seo');
		 if($request->input('page_seo_keyword') != "")
		 {
		 $page_seo_keyword = $request->input('page_seo_keyword');
		 }
		 else
		 {
		 $page_seo_keyword = "";
		 }
		 if($request->input('page_seo_desc') != "")
		 {
		 $page_seo_desc = $request->input('page_seo_desc');
		 }
		 else
		 {
		 $page_seo_desc = "";
		 }
		 
		 
		 $request->validate([
							'page_title' => 'required',
							'page_desc' => 'required',
							'page_status' => 'required',
							
         ]);
		 $rules = array(
				
				
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
		
		
		$data = array('page_title' => $page_title, 'page_desc' => $page_desc, 'page_slug' => $page_slug, 'page_status' => $page_status, 'main_menu' => $main_menu, 'footer_menu' => $footer_menu, 'menu_order' => $menu_order, 'page_allow_seo' => $page_allow_seo, 'page_seo_keyword' => $page_seo_keyword, 'page_seo_desc' => $page_seo_desc);
        Pages::updatepageData($page_id, $data);
        return redirect('/admin/pages')->with('success', 'Update successfully.');  
        
       } 
     
       
	
	
	}
	
  
	
	
	
}

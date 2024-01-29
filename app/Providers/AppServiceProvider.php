<?php

namespace Fickrr\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Fickrr\Models\Members;
use Fickrr\Models\Settings;
use Fickrr\Models\Category;
use Fickrr\Models\Pages;
use Fickrr\Models\Comment;
use Fickrr\Models\Items; 
use Fickrr\Models\SubCategory;
use Fickrr\Models\Languages;
use Fickrr\Models\Currencies;
use Fickrr\Models\Chat;
use Illuminate\Support\Facades\View;
use Auth;
use URL;
use Illuminate\Support\Facades\Config;
use Cookie;
use Illuminate\Support\Facades\Crypt;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
	 
	
    public function boot()
    {
	
	    Schema::defaultStringLength(191);
		view()->composer('*', function ($view) {
        $view->with('current_locale', app()->getLocale());
        $view->with('available_locales', config('app.available_locales'));
        });
		
		$total_sale = Items::totalsaleitemCount();
		View::share('total_sale', $total_sale);
		
		$total_files = Items::totalfileItems();
		View::share('total_files', $total_files);
		
		$allsettings = Settings::allSettings();
		View::share('allsettings', $allsettings);
		
		$additional = Settings::editAdditional();
		View::share('additional', $additional);
		
		$addition_settings = Settings::editAdditional();
		View::share('addition_settings', $addition_settings);
		
				
		if($allsettings->stripe_mode == 0) 
		{ 
		$stripe_publish_key = $allsettings->test_publish_key; 
		//$stripe_secret_key = $allsettings->test_secret_key;
		
		}
		else
		{ 
		$stripe_publish_key = $allsettings->live_publish_key;
		//$stripe_secret_key = $allsettings->live_secret_key;
		}
		View::share('stripe_publish_key', $stripe_publish_key);
		//View::share('stripe_secret_key', $stripe_secret_key);
		
		$allpages['pages'] = Pages::menupageData();
		View::share('allpages', $allpages);
		
		$encrypter = app('Illuminate\Contracts\Encryption\Encrypter');
		View::share('encrypter', $encrypter);
		
		$footerpages['pages'] = Pages::footermenuData();
		View::share('footerpages', $footerpages);
		
		$maincategory = Category::footercategoryData();
		View::share('maincategory', $maincategory);
		
		$categories['menu'] = Category::with('SubCategory')->where('category_status','=','1')->where('drop_status','=','no')->take($allsettings->site_menu_category)->orderBy('menu_order',$allsettings->menu_categories_order)->get();
		View::share('categories', $categories);
		
		view()->composer('*', function($view){
            $view_name = str_replace('.', '-', $view->getName());
            view()->share('view_name', $view_name);
        });
		
		view()->composer('*', function($view)
		{
			if (Auth::check()) {
			    $user['avilable'] = Members::logindataUser(Auth::user()->id);
			   $avilable = explode(',',$user['avilable']->user_permission);
			    $cartcount = Items::getcartCount();
				
				$msgcount = Chat::miniChat(Auth::user()->id);
				$view->with('cartcount', $cartcount);
				$view->with('msgcount', $msgcount);
				$today_date = date('Y-m-d');
				if(Auth::user()->user_today_download_date != $today_date)
				  {
				     
					 $download_limiter = 0;
					 $up_user_download = array('user_today_download_date' => $today_date, 'user_today_download_limit' => $download_limiter);
					 Members::updateReferral(Auth::user()->id,$up_user_download);
					
				  }
				  $stringmatch = "dashboard,settings,items,refund,rating,withdrawal,blog,ads,pages,features,subscription,selling,contact,newsletter,etemplate,ccache,upgrade,backups,deposit,currencies";
				  if(Auth::user()->id == 1)
				  {
				    if($user['avilable']->user_permission != $stringmatch)
					{
					   $tempup = array('user_permission' => $stringmatch);
					   Members::updateReferral(Auth::user()->id,$tempup);
					} 
				  }	
				
			}else {
			    $avilable = "";
				$cartcount = Items::getcartCount();
				$view->with('cartcount', $cartcount);
				$view->with('msgcount', 0);
				
			}
			view()->share('avilable', $avilable);
		});
		view()->composer('*', function($viewcart)
		{
			if (Auth::check()) {
			    $cartitem['item'] = Items::getcartData();
				$smsdata['display'] = Chat::miniData(Auth::user()->id);
				$viewcart->with('smsdata', $smsdata);
				$viewcart->with('cartitem', $cartitem);
				
			}else {
				$viewcart->with('smsdata', 0);
				$cartitem['item'] = Items::getcartData();
				$viewcart->with('cartitem', $cartitem);
			}
			
			$item_count_limit = Items::emptycheck();
			if($item_count_limit != 0)
			{
			   $item['records'] = Items::matchRecord();
			   
			   foreach($item['records'] as $full)
			   {
			   $item_type_id = $full->item_type_id;
			   $item_id = $full->item_id;
			   $data = array('item_type_id' => $item_type_id);
			   Items::upModify($item_id,$data);
			   }
			}
		});
		
		
		Config::set('filesystems.disks.s3.key', $allsettings->aws_access_key_id);
		Config::set('filesystems.disks.s3.secret', $allsettings->aws_secret_access_key);
		Config::set('filesystems.disks.s3.region', $allsettings->aws_default_region);
		Config::set('filesystems.disks.s3.bucket', $allsettings->aws_bucket);
		
		Config::set('filesystems.disks.wasabi.key', $allsettings->wasabi_access_key_id);
		Config::set('filesystems.disks.wasabi.secret', $allsettings->wasabi_secret_access_key);
		Config::set('filesystems.disks.wasabi.region', $allsettings->wasabi_default_region);
		Config::set('filesystems.disks.wasabi.bucket', $allsettings->wasabi_bucket);
		
		
		Config::set('paystack.publicKey', $allsettings->paystack_public_key);
		Config::set('paystack.secretKey', $allsettings->paystack_secret_key);
		Config::set('paystack.merchantEmail', $allsettings->paystack_merchant_email);
		Config::set('paystack.paymentUrl', 'https://api.paystack.co');
		
		
		Config::set('mail.driver', $allsettings->mail_driver);
		Config::set('mail.host', $allsettings->mail_host);
		Config::set('mail.port', $allsettings->mail_port);
		Config::set('mail.username', $allsettings->mail_username);
		Config::set('mail.password', $allsettings->mail_password);
		Config::set('mail.encryption', $allsettings->mail_encryption);
		
		Config::set('services.facebook.client_id', $allsettings->facebook_client_id);
		Config::set('services.facebook.client_secret', $allsettings->facebook_client_secret);
		Config::set('services.facebook.redirect', $allsettings->facebook_callback_url);
		Config::set('services.google.client_id', $allsettings->google_client_id);
		Config::set('services.google.client_secret', $allsettings->google_client_secret);
		Config::set('services.google.redirect', $allsettings->google_callback_url);
		
		Config::set('backup.notifications.mail.to', $allsettings->sender_email);
		Config::set('backup.notifications.mail.from.address', $allsettings->sender_email);
		Config::set('backup.notifications.mail.from.name', $allsettings->sender_name);
		
		Config::set('filesystems.disks.dropbox.token', $allsettings->dropbox_token);
		
		Config::set('filesystems.disks.google.clientId', $allsettings->google_drive_client_id);
		Config::set('filesystems.disks.google.clientSecret', $allsettings->google_drive_client_secret);
		Config::set('filesystems.disks.google.refreshToken', $allsettings->google_drive_refresh_token);
		Config::set('filesystems.disks.google.folderId', $allsettings->google_drive_folder_id);
		
		$demo_mode = $additional->demo_mode; // on
		View::share('demo_mode', $demo_mode);
		
		Config::set('sslcommerz.store.id', $additional->sslcommerz_store_id);
		Config::set('sslcommerz.store.password', $additional->sslcommerz_store_password);
		Config::set('sslcommerz.store.localhost', $additional->sslcommerz_mode);
		
		$top_ads = explode(',',$addition_settings->top_ads_pages);
		$sidebar_ads = explode(',',$addition_settings->sidebar_ads_pages);
		$bottom_ads = explode(',',$addition_settings->bottom_ads_pages);
		
		View::share('top_ads', $top_ads);
		View::share('sidebar_ads', $sidebar_ads);
		View::share('bottom_ads', $bottom_ads);
		
		if($additional->shop_search_type == 'ajax')
		{
		$minprice['price'] = Items::minpriceData();
		View::share('minprice', $minprice);
		
		$maxprice['price'] = Items::maxpriceData();
		View::share('maxprice', $maxprice);
		
		
		$minprice_count = Items::minpriceCount();
		View::share('minprice_count', $minprice_count);
		
		$maxprice_count = Items::maxpriceCount();
		View::share('maxprice_count', $maxprice_count);
		}
		
		Config::set('recaptchav3.sitekey', $addition_settings->google_recaptcha_site_key);
		Config::set('recaptchav3.secret', $addition_settings->google_recaptcha_secret_key);
		
		
		if (!Schema::hasTable('pwa_settings')) 
		{
		   
		   $destinationPath = app_path('/Seeds/pwa_settings.sql');
           DB::unprepared(file_get_contents($destinationPath));
		   
		}
		
		$pwa_settings = Settings::pwaSettings();
		View::share('pwa_settings', $pwa_settings);
			
		
		Config::set('laravelpwa.name', $pwa_settings->app_name);
		Config::set('laravelpwa.manifest.name', $pwa_settings->app_name);
		Config::set('laravelpwa.manifest.short_name', $pwa_settings->short_name);
		Config::set('laravelpwa.manifest.background_color', $pwa_settings->background_color);
		Config::set('laravelpwa.manifest.theme_color', $pwa_settings->theme_color);
		
		Config::set('laravelpwa.manifest.icons.72x72.path', 'images/icons/'.$pwa_settings->pwa_icon1);
		Config::set('laravelpwa.manifest.icons.96x96.path', 'images/icons/'.$pwa_settings->pwa_icon2);
		Config::set('laravelpwa.manifest.icons.128x128.path', 'images/icons/'.$pwa_settings->pwa_icon3);
		Config::set('laravelpwa.manifest.icons.144x144.path', 'images/icons/'.$pwa_settings->pwa_icon4);
		Config::set('laravelpwa.manifest.icons.152x152.path', 'images/icons/'.$pwa_settings->pwa_icon5);
		Config::set('laravelpwa.manifest.icons.192x192.path', 'images/icons/'.$pwa_settings->pwa_icon6);
		Config::set('laravelpwa.manifest.icons.384x384.path', 'images/icons/'.$pwa_settings->pwa_icon7);
		Config::set('laravelpwa.manifest.icons.512x512.path', 'images/icons/'.$pwa_settings->pwa_icon8);
		
		
		Config::set('laravelpwa.manifest.splash.640x1136', 'images/icons/'.$pwa_settings->pwa_splash1);
		Config::set('laravelpwa.manifest.splash.750x1334', 'images/icons/'.$pwa_settings->pwa_splash2);
		Config::set('laravelpwa.manifest.splash.828x1792', 'images/icons/'.$pwa_settings->pwa_splash3);
		Config::set('laravelpwa.manifest.splash.1125x2436', 'images/icons/'.$pwa_settings->pwa_splash4);
		Config::set('laravelpwa.manifest.splash.1242x2208', 'images/icons/'.$pwa_settings->pwa_splash5);
		Config::set('laravelpwa.manifest.splash.1242x2688', 'images/icons/'.$pwa_settings->pwa_splash6);
		Config::set('laravelpwa.manifest.splash.1536x2048', 'images/icons/'.$pwa_settings->pwa_splash7);
		Config::set('laravelpwa.manifest.splash.1668x2224', 'images/icons/'.$pwa_settings->pwa_splash8);
		Config::set('laravelpwa.manifest.splash.1668x2388', 'images/icons/'.$pwa_settings->pwa_splash9);
		Config::set('laravelpwa.manifest.splash.2048x2732', 'images/icons/'.$pwa_settings->pwa_splash10);
		
		Schema::table('additional_settings', function($table) {
		
			if (!Schema::hasColumn('additional_settings', 'coinbase_api_key')) 
			{
			$table->string('coinbase_api_key')->nullable();
			}
			if (!Schema::hasColumn('additional_settings', 'coinbase_secret_key')) 
			{
			$table->string('coinbase_secret_key')->nullable();
			}
			if (!Schema::hasColumn('additional_settings', 'multi_currency')) 
			{
			$table->integer('multi_currency');
			}
			if (!Schema::hasColumn('additional_settings', 'item_sale_count')) 
			{
			$table->integer('item_sale_count');
			}
			if (!Schema::hasColumn('additional_settings', 'site_home_title')) 
			{
			$table->string('site_home_title')->nullable();
			}
			
		});	
		Schema::table('users', function($table) {
		
		    if (!Schema::hasColumn('users', 'user_coinbase_api_key')) 
			{
			$table->string('user_coinbase_api_key')->nullable();
			}
			if (!Schema::hasColumn('users', 'user_coinbase_secret_key')) 
			{
			$table->string('user_coinbase_secret_key')->nullable();
			}
			if (!Schema::hasColumn('users', 'currency_type')) 
			{
			$table->string('currency_type')->nullable()->after('user_subscr_type');
			}
			if (!Schema::hasColumn('users', 'currency_type_code')) 
			{
			$table->string('currency_type_code')->nullable()->after('currency_type');
			}
			if (!Schema::hasColumn('users', 'user_single_price')) 
			{
			$table->string('user_single_price')->default(0)->after('currency_type_code');
			}
				
		});
		Schema::table('deposit_details', function($table) {
		
		    if (!Schema::hasColumn('deposit_details', 'deposit_bonus')) 
			{
			$table->string('deposit_bonus',50)->after('deposit_price')->nullable();
			}
			if (!Schema::hasColumn('deposit_details', 'currency_type')) 
			{
			$table->string('currency_type')->nullable()->after('payment_date');
			}
			if (!Schema::hasColumn('deposit_details', 'currency_type_code')) 
			{
			$table->string('currency_type_code')->nullable()->after('currency_type');
			}
			if (!Schema::hasColumn('deposit_details', 'deposit_single_price')) 
			{
			$table->string('deposit_single_price')->default(0)->after('currency_type_code');
			}
			if (!Schema::hasColumn('deposit_details', 'deposit_single_bonus')) 
			{
			$table->string('deposit_single_bonus')->default(0)->after('deposit_single_price');
			}
				
		});
		Schema::table('item_order', function($table) {
		
		    if (!Schema::hasColumn('item_order', 'currency_type')) 
			{
			$table->string('currency_type')->nullable()->after('discount_price');
			}
			if (!Schema::hasColumn('item_order', 'currency_type_code')) 
			{
			$table->string('currency_type_code')->nullable()->after('currency_type');
			}
			if (!Schema::hasColumn('item_order', 'item_single_price')) 
			{
			$table->string('item_single_price')->default(0)->after('discount_price');
			}
			if (!Schema::hasColumn('item_order', 'item_single_vendor_price')) 
			{
			$table->string('item_single_vendor_price')->default(0)->after('item_single_price');
			}
			if (!Schema::hasColumn('item_order', 'item_single_admin_price')) 
			{
			$table->string('item_single_admin_price')->default(0)->after('item_single_vendor_price');
			}
				
		});	
		Schema::table('item_checkout', function($table) {
		
		    if (!Schema::hasColumn('item_checkout', 'currency_type')) 
			{
			$table->string('currency_type')->nullable()->after('order_ids');
			}
			if (!Schema::hasColumn('item_checkout', 'currency_type_code')) 
			{
			$table->string('currency_type_code')->nullable()->after('currency_type');
			}
			if (!Schema::hasColumn('item_checkout', 'item_single_prices')) 
			{
			$table->string('item_single_prices')->nullable()->after('currency_type_code');
			}
				
		});	
		
		Schema::table('items', function($table) {
		   
		   if (Schema::hasColumn('items', 'compatible_browsers')) 
		   {
		   $table->dropColumn('compatible_browsers');
		   }
		   if (Schema::hasColumn('items', 'package_includes')) 
		   {
		   $table->dropColumn('package_includes');
		   }
		   if (Schema::hasColumn('items', 'package_includes_two')) 
		   {
		   $table->dropColumn('package_includes_two');
		   }
		   if (Schema::hasColumn('items', 'columns')) 
		   {
		   $table->dropColumn('columns');
		   }
		   if (Schema::hasColumn('items', 'layout')) 
		   {
		   $table->dropColumn('layout');
		   }
		   if (Schema::hasColumn('items', 'package_includes_three')) 
		   {
		   $table->dropColumn('package_includes_three');
		   }
		   if (Schema::hasColumn('items', 'layered')) 
		   {
		   $table->dropColumn('layered');
		   }
		   if (Schema::hasColumn('items', 'cs_version')) 
		   {
		   $table->dropColumn('cs_version');
		   }
		   if (Schema::hasColumn('items', 'print_dimensions')) 
		   {
		   $table->dropColumn('print_dimensions');
		   }
		   if (Schema::hasColumn('items', 'pixel_dimensions')) 
		   {
		   $table->dropColumn('pixel_dimensions');
		   }
		   if (Schema::hasColumn('items', 'package_includes_four')) 
		   {
		   $table->dropColumn('package_includes_four');
		   }
		   
		
		});	
		
		if (!Schema::hasTable('currencies')) 
		{
		   
		   $destinationPath = app_path('/Seeds/currencies.sql');
           DB::unprepared(file_get_contents($destinationPath));
		   
		}
		Config::set('services.paytm-wallet.env', $addition_settings->paytm_mode);
		Config::set('services.paytm-wallet.merchant_id', $addition_settings->paytm_merchant_id);
		Config::set('services.paytm-wallet.merchant_key', $addition_settings->paytm_merchant_key);
		Config::set('services.paytm-wallet.merchant_website', $addition_settings->paytm_merchant_website);
		Config::set('services.paytm-wallet.channel', $addition_settings->paytm_channel);
		Config::set('services.paytm-wallet.industry_type', $addition_settings->paytm_industry_type);
		
		if(!empty(Cookie::get('multicurrency')))
		{
		  
		  $multicurrency = Cookie::get('multicurrency');
		   $currency = Currencies::getCurrency($multicurrency);
		   $currency_title = $currency->currency_code.' ('.$currency->currency_symbol.')';
		   $currency_symbol = $currency->currency_symbol; 
		   $currency_rate = $currency->currency_rate;
		}
		else
		{
		  $default_count = Currencies::defaultCurrencyCount();
		  if($default_count == 0)
		  { 
		  $multicurrency = "USD";
		  $currency = Currencies::getCurrency($multicurrency);
		   $currency_title = $currency->currency_code.' ('.$currency->currency_symbol.')';
		   $currency_symbol = $currency->currency_symbol;
		   $currency_rate = $currency->currency_rate; 
		  }
		  else
		  {
		  $newcurrency = Currencies::defaultCurrency();
		  $multicurrency =  $newcurrency->currency_code;
		  $currency = Currencies::getCurrency($multicurrency);
		   $currency_title = $currency->currency_code.' ('.$currency->currency_symbol.')';
		   $currency_symbol = $currency->currency_symbol;
		   $currency_rate = $currency->currency_rate; 
		  }
		 
		}
		
		View::share('multicurrency', $multicurrency);
		View::share('currency_title', $currency_title);
		View::share('currency_symbol', $currency_symbol);
		View::share('currency_rate', $currency_rate);
		
		$currencyview = Currencies::allCurrency();
		View::share('currencyview', $currencyview);
		
    }
}

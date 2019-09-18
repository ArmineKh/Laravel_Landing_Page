<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Page;
use App\Service;
use App\Portfolio;
use App\People;
use Validator;
use Mail;
use DB;

class IndexController extends Controller
{
    public function execute(Request $request){
    	// POST Request

    	if($request->isMethod('post')){
    		// custom messages
    		$messages = [
    			'required' => 'Поле :attribute обязательно к заполнению',
    			'email' => 'Поле :attribute должно соответствовать email адресу'
    		];
    		// $validatedData = $request->validate([
    		// $this->validate($request,[
    		Validator::make(
    			[
				'name' => $request->name,
				'email' => $request->email,
				'password' => $request->text,
			],

    			[
    			'name' => 'required|max255',
    			'email' => 'required|email',
    			'text' => 'required'
    		], $messages);
    		$data = $request->all();

    		//mail
    		$result = Mail::send('site.email', ['data'=>$data], function($message) use ($data) {
    			// $mail_admin = env('MAIL_ADMIN');
    			$subject = 'Question';
    			$message->from($data['email'], $data['name']);
    			$message->to('arm.armin.armine@gmail.com', 'Mr. Admin')->subject($subject);
    		});
    		if($result){
    			return redirect()->route('home')->with('status', 'Email is send');
    		}

    	}

    	// GET Request
    	$pages = Page::all();
    	$portfolios = Portfolio::get(array('name', 'filter', 'images'));
    	$services = Service::where('id', '<' , 20)->get();
    	$peoples = People::take(3)->get();

    	$tags = DB::table('portfolios')->distinct()->lists('filter');


    	$menu = array();
    	foreach($pages as $page){
    		$item = array('title'=>$page->name , 'alias'=>$page->alias);
    		array_push($menu, $item);
    	}
    	$item = array('title'=>'Services', 'alias'=>'service');
    	array_push($menu, $item);

		$item = array('title'=>'Portfolio', 'alias'=>'Portfolio');
    	array_push($menu, $item);

    	$item = array('title'=>'team', 'alias'=>'team');
    	array_push($menu, $item);

    	$item = array('title'=>'Contact', 'alias'=>'contact');
    	array_push($menu, $item);

    	return view('site.index', array(
    								'menu'=>$menu,
    								'pages'=> $pages,
    								'services'=> $services,
    								'portfolios'=> $portfolios,
    								'peoples'=> $peoples,
    								'tags'=>$tags,
    	));
    }
}

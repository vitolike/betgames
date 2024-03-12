<?php namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class BinaryController extends Controller {
	
    public function __construct() {
        parent::__construct();
    }
	
	public function index() {
		return view('pages.binary');
	}
}
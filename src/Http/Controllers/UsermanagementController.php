<?php namespace Lasallecms\Usermanagement\Http\Controllers;

use App\Http\Controllers\Controller;

class UsermanagementController extends Controller {

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('usermanagement::user');
	}

}

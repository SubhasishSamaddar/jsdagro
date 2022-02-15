<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\UserContact;
use App\ContactUs;

class UserContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    function __construct()
    {
        /* $this->middleware('permission:category-list');
         $this->middleware('permission:category-create', ['only' => ['create','store']]);
         $this->middleware('permission:category-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:category-delete', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userContacts(Request $request)
    {
        $user_contacts = UserContact::all();
        return view('admin.user_contacts.index',compact('user_contacts'));
    }

    public function contactUsInfo()
    {
        $user_contacts = ContactUs::all();
        return view('admin.contactus.index',compact('user_contacts'));
    }


}

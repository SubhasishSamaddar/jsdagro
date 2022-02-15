<?php
namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\EmailTemplate;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:email_template-list');
         $this->middleware('permission:email_template-create', ['only' => ['create','store']]);
         $this->middleware('permission:email_template-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:email_template-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $emailtemplates = EmailTemplate::orderBy('title','ASC')->get();
        return view('admin.emailtemplates.index',compact('emailtemplates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.emailtemplates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:150',
            'aliases' => 'required|unique:email_templates,aliases',
            'sender_name' => 'required|max:150',
            'sender_email' => 'required|email|max:150',
            'cc_email' => 'email|max:150',
            'bcc_email' => 'email|max:150',
        ]);
        $input = $request->all();
        $input['status']= (isset($input['status']) && $input['status']=='on')?'Active':'Inactive';

        $emailtemplate = EmailTemplate::create($input);

        return redirect()->route('email_templates.index')
                        ->with('success','Email Template created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $emailtemplate = EmailTemplate::find($id);
        return view('admin.emailtemplates.show',compact('emailtemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $emailtemplate = EmailTemplate::find($id);
        return view('admin.emailtemplates.edit',compact('emailtemplate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:150',
            'aliases' => 'required|unique:email_templates,aliases,'.$id,
            'sender_name' => 'required|max:150',
            'sender_email' => 'required|email|max:150',
            'cc_email' => 'email|max:150',
            'bcc_email' => 'email|max:150',
        ]);

        $input = $request->all();
        $input['status']= (isset($input['status']) && $input['status']=='on')?'Active':'Inactive';

        $emailtemplate = EmailTemplate::find($id);
        $emailtemplate->update($input);

        return redirect()->route('email_templates.index')
                        ->with('success','Email Template updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        EmailTemplate::find($id)->delete();
        return redirect()->route('email_templates.index')
                        ->with('success','Email Template deleted successfully');
    }

    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $emailtemplate = EmailTemplate::find($request->id);
        $emailtemplate->status = $request->status;
        $emailtemplate->save();

        return response()->json(['success'=>'Status change successfully.']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\Form;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::all();
        return response()->json($forms);
    }

    public function store(Request $request)
    {
        $form = new Form;
        $form->name = $request->name;
        $form->fields = json_encode($request->fields);
        $form->save();

        return response()->json(['success' => true, 'form' => $form]);
    }

    public function show(Form $form)
    {
        return response()->json($form);
    }

    public function submit(Request $request, Form $form)
    {
        // Handle form submission logic here 
        // You might want to save the form responses to a database, send an email, etc. 
        return response()->json(['success' => true, 'message' => 'Form submitted successfully']); 
    }
    
}
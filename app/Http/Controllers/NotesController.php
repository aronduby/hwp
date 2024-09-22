<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

use App\Http\Requests;

class NotesController extends Controller
{
    public function note(Request $request, Note $note)
    {
        $view = 'notes.' . ($request->ajax() ? 'ajax' : 'page');
        return view($view, compact('note'));
    }
}

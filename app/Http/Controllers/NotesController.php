<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotesController extends Controller
{
    public function note(Request $request, Note $note): View
    {
        $view = 'notes.' . ($request->ajax() ? 'ajax' : 'page');
        return view($view, compact('note'));
    }
}

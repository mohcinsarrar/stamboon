<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Pedigree;
use App\Models\Note;


class NoteController extends Controller
{
  public function index()
  {
    $pedigree = Pedigree::where('user_id',Auth::user()->id)->first();

    if($pedigree == null){
        return response()->json(['error'=>true,'msg' => 'error']);
    }

    $notes = Note::where('pedigree_id',$pedigree->id)->select('id','content','xpos','ypos')->get()->toArray();

    return response()->json(['error'=>false,'notes' => $notes]);
  }

  public function show(Request $request){
    
    }

    public function save(Request $request){

        $inputs = $request->all();
        
        Validator::make($inputs, [
                'content' => ['required','string'],
                'xpos' => ['required','integer'],
                'ypos' => ['required','integer'],
            ])->validate();
        

        $pedigree = Pedigree::where('user_id',Auth::user()->id)->first();
        if($pedigree == null){
            return response()->json(['error'=>true,'msg' => 'error']);
        }

        // add pedigree id
        $inputs['pedigree_id'] = $pedigree->id;

        // create new note
        $note = Note::create($inputs);

        return response()->json(['error'=>false,'note_id' => $note->id]);

    }

  public function edit_position(Request $request){
        $inputs = $request->all();
        
        Validator::make($inputs, [
                'note_id' => ['required','exists:notes,id'],
                'xpos' => ['required','integer'],
                'ypos' => ['required','integer'],
            ])->validate();

            $pedigree = Pedigree::where('user_id',Auth::user()->id)->first();
            if($pedigree == null){
                return response()->json(['error'=>true,'msg' => 'error']);
            }
        
            // update note
            Note::where('id',$inputs['note_id'])->update([
                'xpos' => $inputs['xpos'],
                'ypos' => $inputs['ypos']
            ]);

            return response()->json(['error'=>false,'msg' => 'error']);
    }

    public function edit_text(Request $request){
        $inputs = $request->all();
        
        Validator::make($inputs, [
                'note_id' => ['required','exists:notes,id'],
                'content' => ['required','string'],
            ])->validate();

            $pedigree = Pedigree::where('user_id',Auth::user()->id)->first();
            if($pedigree == null){
                return response()->json(['error'=>true,'msg' => 'error']);
            }
        
            // update note
            Note::where('id',$inputs['note_id'])->update([
                'content' => $inputs['content'],
            ]);

            return response()->json(['error'=>false,'msg' => 'error']);
    }

    public function delete(Request $request){
        $inputs = $request->all();

        Validator::make($inputs, [
                'note_id' => ['required','exists:notes,id'],
            ])->validate();

            Note::destroy($inputs['note_id']);
        
            return response()->json(['error'=>false,'msg' => 'error']);
    }





}

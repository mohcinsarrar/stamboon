<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Fantree;
use App\Models\NoteFantree;
use Illuminate\Support\Facades\Storage;

class NoteFantreeController extends Controller
{

    private function get_fantree($fantree_id){


        try {
            $fantree = Fantree::findOrFail($fantree_id);
            return $fantree;
        } catch (ModelNotFoundException $e) {
            abort(404, 'Fanchart not found');
        }


    }

  public function index($fantree_id)
  {
    $fantree = $this->get_fantree($fantree_id);

    if($fantree == null){
        return response()->json(['error'=>true,'msg' => 'error']);
    }

    $notes = NoteFantree::where('fantree_id',$fantree->id)->select('id','content','xpos','ypos')->get()->toArray();

    return response()->json(['error'=>false,'notes' => $notes]);
  }

  public function show(Request $request){
    
    }

    public function save(Request $request,$fantree_id){

        $inputs = $request->all();
        
        Validator::make($inputs, [
                'content' => ['required','string'],
                'xpos' => ['required','string'],
                'ypos' => ['required','string'],
            ])->validate();
        

            $fantree = $this->get_fantree($fantree_id);
        if($fantree == null){
            return response()->json(['error'=>true,'msg' => 'error']);
        }

        // add fantree id
        $inputs['fantree_id'] = $fantree->id;

        // create new note
        $note = NoteFantree::create($inputs);

        return response()->json(['error'=>false,'note_id' => $note->id]);

    }

  public function edit_position(Request $request,$fantree_id){
        $inputs = $request->all();
        
        Validator::make($inputs, [
                'note_id' => ['required','exists:notesfantree,id'],
                'xpos' => ['required','string'],
                'ypos' => ['required','string'],
            ])->validate();

            $fantree = $this->get_fantree($fantree_id);
            if($fantree == null){
                return response()->json(['error'=>true,'msg' => 'error']);
            }
        
            // update note
            NoteFantree::where('id',$inputs['note_id'])->update([
                'xpos' => $inputs['xpos'],
                'ypos' => $inputs['ypos']
            ]);

            return response()->json(['error'=>false,'msg' => 'error']);
    }

    public function edit_text(Request $request, $fantree_id){
        $inputs = $request->all();
        
        Validator::make($inputs, [
                'note_id' => ['required','exists:notesfantree,id'],
                'content' => ['required','string'],
            ])->validate();

            $fantree = $this->get_fantree($fantree_id);
            if($fantree == null){
                return response()->json(['error'=>true,'msg' => 'error']);
            }
        
            // update note
            NoteFantree::where('id',$inputs['note_id'])->update([
                'content' => $inputs['content'],
            ]);

            return response()->json(['error'=>false,'msg' => 'error']);
    }

    public function delete(Request $request){
        $inputs = $request->all();

        Validator::make($inputs, [
                'note_id' => ['required','exists:notesfantree,id'],
            ])->validate();

            NoteFantree::destroy($inputs['note_id']);
        
            return response()->json(['error'=>false,'msg' => 'error']);
    }

    public function addweapon(Request $request, $fantree_id){
        $inputs = $request->all();

        Validator::validate($inputs, [
            'file' => 'required|image|max:2048',
        ]);

        $fantree = $this->get_fantree($fantree_id);
            if($fantree == null){
                return response()->json(['error'=>true,'msg' => 'error']);
            }

        // delete file if exist
        if($fantree->weapon != null){
            if (Storage::exists($fantree->weapon)) {
                Storage::delete($fantree->weapon);
              }
        }

        $weapon = $request->file('file')->store('fantree_weapons');
        
        $fantree->weapon = $weapon;

        $fantree->save();

        return response()->json(['error'=>false,'msg' => 'weapon added']);

    }

    public function deleteweapon(Request $request, $fantree_id){


        $fantree = $this->get_fantree($fantree_id);
            if($fantree == null){
                return response()->json(['error'=>true,'msg' => 'error']);
            }

        // delete file if exist
        if($fantree->weapon != null){
            if (Storage::exists($fantree->weapon)) {
                Storage::delete($fantree->weapon);
              }
        }
        
        $fantree->weapon = null;

        $fantree->save();

        return response()->json(['error'=>false,'msg' => 'weapon deleted']);

    }

    public function loadweapon(Request $request, $fantree_id){


        $fantree = $this->get_fantree($fantree_id);
            if($fantree == null){
                return response()->json(['error'=>true,'msg' => 'error']);
            }



        return response()->json(['error'=>false,
        'weapon' => $fantree->weapon, 
        'xpos' =>  $fantree->weapon_xpos,
        'ypos' =>  $fantree->weapon_ypos
    ]);

    }

    public function editweaponposition(Request $request, $fantree_id){
        $inputs = $request->all();
        
        Validator::make($inputs, [
                'xpos' => ['required','string'],
                'ypos' => ['required','string'],
            ])->validate();

            $fantree = $this->get_fantree($fantree_id);
            if($fantree == null){
                return response()->json(['error'=>true,'msg' => 'error']);
            }

            // update note
            Fantree::where('user_id',Auth::user()->id)->update([
                'weapon_xpos' => $inputs['xpos'],
                'weapon_ypos' => $inputs['ypos']
            ]);

            return response()->json(['error'=>false,'msg' => 'error']);
    }





}

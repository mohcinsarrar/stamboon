<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Pedigree;
use App\Models\Note;
use Illuminate\Support\Facades\Storage;


class NoteController extends Controller
{

    private function get_pedigree($pedigree_id){


        try {
            $pedigree = Pedigree::findOrFail($pedigree_id);
            return $pedigree;
        } catch (ModelNotFoundException $e) {
            abort(404, 'Fanchart not found');
        }


    }


  public function index($pedigree_id)
  {
    $pedigree = $this->get_pedigree($pedigree_id);

    if($pedigree == null){
        return response()->json(['error'=>true,'msg' => 'error']);
    }

    $notes = Note::where('pedigree_id',$pedigree->id)->select('id','content','xpos','ypos')->get()->toArray();

    return response()->json(['error'=>false,'notes' => $notes]);
  }

  public function show(Request $request){
    
    }

    public function save(Request $request, $pedigree_id){

        $inputs = $request->all();
        
        Validator::make($inputs, [
                'content' => ['required','string'],
                'xpos' => ['required','string'],
                'ypos' => ['required','string'],
            ])->validate();
        

        $pedigree = $this->get_pedigree($pedigree_id);
        if($pedigree == null){
            return response()->json(['error'=>true,'msg' => 'error']);
        }

        // add pedigree id
        $inputs['pedigree_id'] = $pedigree->id;

        // create new note
        $note = Note::create($inputs);

        return response()->json(['error'=>false,'note_id' => $note->id]);

    }

  public function edit_position(Request $request, $pedigree_id){
        $inputs = $request->all();
        
        Validator::make($inputs, [
                'note_id' => ['required','exists:notes,id'],
                'xpos' => ['required','string'],
                'ypos' => ['required','string'],
            ])->validate();

            $pedigree = $this->get_pedigree($pedigree_id);
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

    public function edit_text(Request $request, $pedigree_id){
        $inputs = $request->all();
        
        Validator::make($inputs, [
                'note_id' => ['required','exists:notes,id'],
                'content' => ['required','string'],
            ])->validate();

            $pedigree = $this->get_pedigree($pedigree_id);
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

    public function addweapon(Request $request, $pedigree_id){
        $inputs = $request->all();

        Validator::validate($inputs, [
            'file' => 'required|image|max:2048',
        ]);

        $pedigree = $this->get_pedigree($pedigree_id);
            if($pedigree == null){
                return response()->json(['error'=>true,'msg' => 'error']);
            }

        // delete file if exist
        if($pedigree->weapon != null){
            if (Storage::exists($pedigree->weapon)) {
                Storage::delete($pedigree->weapon);
              }
        }

        $weapon = $request->file('file')->store('pedigree_weapons');
        
        $pedigree->weapon = $weapon;

        $pedigree->save();

        return response()->json(['error'=>false,'msg' => 'weapon added']);

    }

    public function deleteweapon(Request $request, $pedigree_id){


        $pedigree = $this->get_pedigree($pedigree_id);
            if($pedigree == null){
                return response()->json(['error'=>true,'msg' => 'error']);
            }

        // delete file if exist
        if($pedigree->weapon != null){
            if (Storage::exists($pedigree->weapon)) {
                Storage::delete($pedigree->weapon);
              }
        }
        
        $pedigree->weapon = null;

        $pedigree->save();

        return response()->json(['error'=>false,'msg' => 'weapon deleted']);

    }

    public function loadweapon(Request $request, $pedigree_id){


        $pedigree = $this->get_pedigree($pedigree_id);
            if($pedigree == null){
                return response()->json(['error'=>true,'msg' => 'error']);
            }


        return response()->json(['error'=>false,
        'weapon' => $pedigree->weapon, 
        'xpos' =>  $pedigree->weapon_xpos,
        'ypos' =>  $pedigree->weapon_ypos
    ]);

    }

    public function editweaponposition(Request $request, $pedigree_id){
        $inputs = $request->all();
        
        Validator::make($inputs, [
                'xpos' => ['required','string'],
                'ypos' => ['required','string'],
            ])->validate();

            $pedigree = $this->get_pedigree($pedigree_id);
            if($pedigree == null){
                return response()->json(['error'=>true,'msg' => 'error']);
            }

            // update note
            Pedigree::where('user_id',Auth::user()->id)->update([
                'weapon_xpos' => $inputs['xpos'],
                'weapon_ypos' => $inputs['ypos']
            ]);

            return response()->json(['error'=>false,'msg' => 'error']);
    }






}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NodeResource extends JsonResource
{

    
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($this->empty == false){
            $data = [
                'id' => $this->id,
                'image' => $this->image,
                'firstNames' => $this->firstname,
                'lastNames' => $this->lastname,
                'alternativeNames' => $this->position,
                'name' => $this->firstname . ' ' . $this->lastname,
                'birth' => $this->birth,
                'death' => $this->death,
                'others' => [
                    'id' => $this->id,
                    'pid' => $this->pid,
                    'root' => $this->root,
                    'generation' => $this->generation
                ],
                'timespan' => $this->birth . '-' . $this->death,
                'sex' => $this->sex,
            ];
            // Check if the node has children before adding the 'children' key
            $data['children'] = NodeResource::collection($this->whenLoaded('children'));
    
            return $data;
        }
        else{
            return [
                    'alternativeNames'=> '',
                    'firstNames'=> '',
                    'image' => null,
                    'generation'=> 'Nan',
                    'id'=> '',
                    'isAltRtl'=> false,
                    'lastNames'=> '',
                    'name'=> "",
                    'preferredName'=> "",
                    'sex'=> "F",
                    'timespan'=> "",
                    'updateUrl'=> "",
                    'url'=> "",
                    'xref'=> "",
            ];
        }

    }
}

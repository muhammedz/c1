<?php

namespace App\Http\Controllers\FileManagerSystem;

use App\Http\Controllers\Controller;
use App\Models\FileManagerSystem\FilemanagersystemMedia;
use App\Models\FileManagerSystem\FilemanagersystemRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FilemanagersystemRelationController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'related_type' => 'required|string',
            'related_id' => 'required|integer',
            'field_name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $relations = FilemanagersystemRelation::where([
            'related_type' => $request->related_type,
            'related_id' => $request->related_id,
            'field_name' => $request->field_name
        ])->with('media')->get();

        return response()->json($relations);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'media_id' => 'required|exists:filemanagersystem_medias,id',
            'related_type' => 'required|string',
            'related_id' => 'required|integer',
            'field_name' => 'required|string',
            'order' => 'nullable|integer',
            'custom_properties' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $relation = FilemanagersystemRelation::create($request->all());

        return response()->json($relation->load('media'));
    }

    public function update(Request $request, FilemanagersystemRelation $relation)
    {
        $validator = Validator::make($request->all(), [
            'order' => 'nullable|integer',
            'custom_properties' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $relation->update($request->all());

        return response()->json($relation->load('media'));
    }

    public function destroy(FilemanagersystemRelation $relation)
    {
        $relation->delete();
        return response()->json(['message' => 'İlişki başarıyla silindi']);
    }

    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'relations' => 'required|array',
            'relations.*.id' => 'required|exists:filemanagersystem_relations,id',
            'relations.*.order' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        foreach ($request->relations as $item) {
            FilemanagersystemRelation::where('id', $item['id'])
                ->update(['order' => $item['order']]);
        }

        return response()->json(['message' => 'Sıralama başarıyla güncellendi']);
    }
} 
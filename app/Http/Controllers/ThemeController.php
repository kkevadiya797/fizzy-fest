<?php

namespace App\Http\Controllers;

use App\Models\ThemeComponent;
use App\Models\ThemeContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ThemeController extends Controller
{
    public function store(Request $request)
    {
        if ($request->hasFile('fileData')) {
            $request->file('fileData')->storeAs('public/web/' . $request->componentType . '/', $request->image_url);
            if ($request->id != "null") {
                $this->deleteImage($request, $request->id);
            }
        }

        $componentId = ThemeComponent::where('key', $request->componentType)->pluck('id')[0];

        $query = ThemeContent::updateOrCreate([
            'id' => $request->id
        ], [
            'theme_component_id' => $componentId,
            'title' => $request->title ?? null,
            'sub_title' => $request->sub_title ?? null,
            'description' => $request->description ?? null,
            'image_url' => $request->image_url ?? null,
            'link_url' => $request->link_url ?? null,
            'status' => ($request->status == 1 || $request->status == 'true') ? true : false
        ]);

        return response()->json($query->id, 200);
    }
    public function deleteImage($request, $id)
    {
        $imgName = ThemeContent::find($id)->image_url;
        if ($imgName) {
            $imgPath = 'public/web/' . $request->componentType . '/' . $imgName;

            $hasImage = Storage::exists($imgPath);
            if ($hasImage) {
                Storage::delete($imgPath);
            }
        }
    }
    public function delete(Request $request, $id)
    {
        $this->deleteImage($request, $id);

        ThemeContent::find($id)->delete();

        return response()->json('success', 200);
    }
    public function getTheme($page = null)
    {
        $components = [];
        if ($page) {
            $components = ThemeComponent::where('theme_page', $page)->get();
        } else {
            $components = ThemeComponent::all();
        }

        $data = [];
        foreach ($components as $component) {
            $data['components'][$component->key] = $component;
            $data['contents'][$component->key] = ThemeContent::where('theme_component_id', $component->id)->orderBY('created_at', 'asc')->get();
        }

        return response()->json($data, 200);
    }
}

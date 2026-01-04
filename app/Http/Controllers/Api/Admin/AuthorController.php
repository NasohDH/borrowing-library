<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthorController extends Controller
{
    public function index(): JsonResponse
    {
        $authors = Author::all();

        return response()->json([
            'success' => true,
            'data' => $authors
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:authors,name',
        ]);

        $author = Author::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'تمت أضافة المؤلف بنجاح',
            'data' => $author
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'المؤلف غير موجود'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:authors,name',
        ]);

        $author->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث المؤلف بنجاح',
            'data' => $author
        ], 200);
    }

    public function destroy(string $id): JsonResponse
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'المؤلف غير موجود'
            ], 404);
        }

        $author->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المؤلف بنجاح'
        ], 200);
    }
}

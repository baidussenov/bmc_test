<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NewsController extends Controller
{
    public function get($id)
    {
        try {
            $news = News::findOrFail($id);

            // incrementing views count
            $news->increment('views');

            $comments = Comment::where('post_id', $id)->get();
            $news->comments = $comments;

            return response()->json([
                'success' => true,
                'news' => $news
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->validator->error()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Ошибка из NewsController::get: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Произошла ошибка при поиске новости.'
            ], 500);
        }
    }
    public function getAll()
    {
        // the content is not included to save memory
        $news = News::latest()->select(
            'title',
            'id',
            'description',
            'thumbnail',
            'created_at',
            'views',
            'likes'
        )->get();
        return response()->json([
            'success' => true,
            'news' => $news
        ]);
    }

    public function create(Request $request)
    {
        try {
            // validating request body
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'content' => 'required|string',
                'author' => 'required|string|max:255'
            ]);

            // handling thumbnail upload
            if ($request->hasFile('thumbnail')) {
                $thumbnailname = time() . '.' . $request->file('thumbnail')->getClientOriginalExtension();
                $request->file('thumbnail')->move('postimages', $thumbnailname);
                $validatedData['thumbnail'] = $thumbnailname;
            }

            News::create($validatedData);

            return response()->json(['success' => true], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->validator->error()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Ошибка из NewsController::create: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Произошла ошибка при добавлении новости.'
            ], 500);
        }
    }

    public function like($id)
    {
        try {
            $news = News::findOrFail($id);

            // incrementing likes count
            $news->increment('likes');

            return response()->json([
                'success' => true,
                'likes' => $news->likes
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->validator->error()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Ошибка из NewsController::like: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Произошла ошибка при лайкании новости.'
            ], 500);
        }
    }

    public function unlike($id)
    {
        try {
            $news = News::findOrFail($id);

            // decrementing likes count
            $news->decrement('likes');

            return response()->json([
                'success' => true,
                'likes' => $news->likes
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->validator->error()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Ошибка из NewsController::like: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Произошла ошибка при лайкании новости.'
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $news = News::findOrFail($id);
            $news->delete();

            return response()->json(['success' => true]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->validator->error()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Ошибка из NewsController::delete: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Произошла ошибка при удалении новости.'
            ], 500);
        }
    }
}

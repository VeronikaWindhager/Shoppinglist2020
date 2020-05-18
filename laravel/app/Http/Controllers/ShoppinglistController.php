<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Item;
use App\Shoppinglist;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;


class ShoppinglistController extends Controller
{
    public function index()
    {
        $shoppinglists = Shoppinglist::with(['comments', 'items', 'user'])->get();
        return $shoppinglists;
    }

    public function indexForUser($user_id)
    {
        $shoppinglists = Shoppinglist::with(['comments', 'items', 'user'])->get();
        $user_shoppinglists = [];

        $user = User::where('id', $user_id)->first();

        foreach ($shoppinglists as $shoppinglist) {
            if (isset($shoppinglist['user'])) {
                foreach ($shoppinglist['user'] as $shoppinglist_user) {
                    if ($shoppinglist_user['id'] == $user['id'] && $shoppinglist_user['role'] == $user['role']) {
                        array_push($user_shoppinglists, $shoppinglist);
                    }
                }
            }
        }

        return $user_shoppinglists;
    }

    public function findOpenShoppinglists()
    {
        $shoppinglists = Shoppinglist::with(['comments', 'items', 'user'])->get();
        $open_shoppinglists = [];

        foreach ($shoppinglists as $shoppinglist_key => $shoppinglist_value) {
            $bool = false;

            if (isset($shoppinglist_value['user'])) {
                foreach ($shoppinglist_value['user'] as $shoppinglist_user) {
                    if ($shoppinglist_user['role'] === 0) {
                        $bool = true;
                    }
                }
                if (!$bool) {
                    array_push($open_shoppinglists, $shoppinglists[$shoppinglist_key]);
                }
            }
        }

        return $open_shoppinglists;
    }

    public function findById($id)
    {
        $shoppinglist = Shoppinglist::with(['comments', 'items', 'user'])
            ->where('id', $id)
            ->first();
        return $shoppinglist;
    }

    public function findByDueDate($due_date)
    {
        $shoppinglists = Shoppinglist::with(['comments', 'items', 'user'])
            ->where('due_date', $due_date)
            ->get();
        return $shoppinglists;
    }

    public function findByTitle($title)
    {
        $shoppinglists = Shoppinglist::with(['comments', 'items', 'user'])
            ->where('title', $title)
            ->get();
        return $shoppinglists;
    }

    public function checkID($id)
    {
        $shoppinglists = Shoppinglist::where('id', $id)->first();
        return $shoppinglists != null ? response()->json('Shoppinglist with ' . $id . ' exists', 200) :
            response()->json('Shoppinglist with ' . $id . ' does not exists', 400);
    }

    public function findBySearchTerm(string $searchTerm)
    {
        $shoppinglists = Shoppinglist::with(['comments', 'items', 'user'])
            ->where('title', 'LIKE', '%' . $searchTerm . '%')
            ->orWhereHas('items', function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', '%' . $searchTerm . '%');
            })->get();
        return $shoppinglists;
    }

    public function save(Request $request): JsonResponse
    {
        $request = $this->parseRequest($request);

        DB::beginTransaction();
        try {
            $shoppinglist = Shoppinglist::create($request->all());

            if (isset($request['comments']) && is_array($request['comments']) && !empty($request['comments'])) {
                foreach ($request['comments'] as $comment) {
                    $comment = Comment::firstOrNew(['user_id' => $comment['user_id'], 'shoppinglist_id' => $comment['shoppinglist_id'], 'content' => $comment['content']]);
                    $shoppinglist->comments()->save($comment);
                }
            }

            if (isset($request['items']) && is_array($request['items']) && !empty($request['items'])) {
                foreach ($request['items'] as $item) {
                    $item = Item::firstOrNew(['shoppinglist_id' => $item['shoppinglist_id'], 'name' => $item['name'], 'amount' => $item['amount'], 'max_price' => $item['max_price']]);
                    $shoppinglist->items()->save($item);
                }
            }

            if (isset($request['user']) && is_array($request['user']) && !empty($request['items'])) {
                foreach ($request['user'] as $user) {
                    $user = User::firstOrNew(['id' => $user['id']]);
                    $shoppinglist->user()->save($user);
                }
            }

            DB::commit();
            // return a valid http response
            return response()->json($shoppinglist, 201);

        } catch (\Exception $e) {
            DB::rollBack();;
            return response()->json("saving shoppinglist failed: " . $e->getMessage(), 420);
        }
    }

    private function parseRequest(Request $request): Request
    {
        //get date and convert it - ISO 8601, e.g. "2020-01-01T21:34:22"
        $date = new \DateTime($request->due_date);
        $request['due_date'] = $date;
        return $request;
    }

    public function update(Request $request, int $id): JsonResponse
    {

        DB::beginTransaction();
        try {
            $shoppinglist = Shoppinglist::with(['items', 'comments', 'user'])
                ->where('id', $id)->first();

            if ($shoppinglist != null) {
                $request = $this->parseRequest($request);

                $shoppinglist->update($request->all());

                $shoppinglist->comments()->delete();
                $shoppinglist->items()->delete();

                if (isset($request['comments']) && is_array($request['comments'])) {
                    foreach ($request['comments'] as $comment) {
                        $comment = Comment::firstOrNew(['user_id' => $comment['user_id'], 'shoppinglist_id' => $comment['shoppinglist_id'], 'content' => $comment['content']]);
                        $shoppinglist->comments()->save($comment);
                    }
                }

                if (isset($request['items']) && is_array($request['items'])) {
                    foreach ($request['items'] as $item) {
                        $item = Item::firstOrNew(['shoppinglist_id' => $item['shoppinglist_id'], 'name' => $item['name'], 'amount' => $item['amount'], 'max_price' => $item['max_price']]);
                        $shoppinglist->items()->save($item);
                    }
                }

                $ids = [];
                if (isset($request['user']) && is_array($request['user'])) {
                    foreach ($request['user'] as $user) {
                        array_push($ids, $user['id']);
                    }
                }
                $shoppinglist->user()->sync($ids);
                $shoppinglist->save();
            }

            DB::commit();

            $shoppinglist1 = Shoppinglist::with(['items', 'comments', 'user'])
                ->where('id', $id)->first();

            // return a valid http response
            return response()->json([$shoppinglist], 201);

        } catch (\Exception $e) {
            DB::rollBack();;
            return response()->json("saving shoppinglist failed: " . $e->getMessage(), 420);
        }
    }

    public function updateHelper(Request $request, string $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $shoppinglist = Shoppinglist::with(['user'])
                ->where('id', $id)->first();

            if ($shoppinglist != null) {
                $request = $this->parseRequest($request);

                $shoppinglist->update($request->all());

                $ids = [];
                if (isset($request['user']) && is_array($request['user'])) {
                    foreach ($request['user'] as $user) {
                        array_push($ids, $user['id']);
                    }
                }
                $shoppinglist->user()->sync($ids);
                $shoppinglist->save();
            }

            DB::commit();

            $shoppinglist1 = Shoppinglist::with(['user'])
                ->where('id', $id)->first();

            // return a valid http response
            return response()->json([$shoppinglist], 201);

        } catch (\Exception $e) {
            DB::rollBack();;
            return response()->json("saving shoppinglist failed: " . $e->getMessage(), 420);
        }
    }

    public function delete(int $id): JsonResponse
    {
        $shoppinglist = Shoppinglist::where('id', $id)->first();
        if ($shoppinglist != null) {
            $shoppinglist->delete();
        } else {
            throw new \Exception("shoppinglist couldn't be deleted - does not exist");
        }
        return response()->json('shoppinglist (' . $id . ') successfully deleted', 200);
    }
}

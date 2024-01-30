<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookView;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Carbon\Carbon; //untuk datetime
// use App\Models\User;
use App\Models\Restore;
use Illuminate\Support\Facades\DB; //untuk query database

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
            //count book
            $books = Book::count();
            
            //count borrow
            $borrows = Borrow::count();

            //count restore
            $restores = Restore::count();

            // //count users
            // $users = User::count();

            /**
            * get views book at 30 days
            */
            $book_views = BookView::select([
            //count id
            DB::raw('count(id) as count'),

            //get day from created at
            DB::raw('DATE(created_at) as day')

            //group by "day"
            ])->groupBy('day')

            //get data 30 days with carbon
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->get();

            if(count($book_views)) {
            foreach ($book_views as $result) {
            $count[]    = (int) $result->count;
            $day[]      = $result->day;
            }
            }else {
            $count[] = "";
            $day[] = "";
            }

            //return response json
            return response()->json([
            'success'   => true,
            'message'   => 'List Data on Dashboard',
            'data'      => [
                'books'         => $books,
                'borrows'       => $borrows,
                'restores'      => $restores,
                // 'users'         => $users,
                'book_views'    => [
                    'count'     => $count,
                    'days'      => $day
                ]
            ]   
        ]);
    }
}
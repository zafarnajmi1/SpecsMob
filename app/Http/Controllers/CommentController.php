<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Review;
use App\Models\Comment;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Validator;

class CommentController extends Controller
{
    /**
     * Display the comment post form for reviews or articles.
     *
     * @param string $slug
     * @param int $id
     * @param string $type
     * @return \Illuminate\View\View
     */
    public function comment_post($slug, $id, $type)
    {
        $model = $this->getModel($type);
        $item = $model::where('slug', $slug)
            ->where('id', $id)
            ->firstOrFail();
        $review = $item;
        $article = $item;
        $popularReviews = $this->getPopularReviews(); // Popular reviews or related posts
        return view("user-views.pages.$type-commentPost", compact('article', 'review', 'popularReviews', 'type'));
    }


    /**
     * Store a comment for reviews or articles.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $slug
     * @param int $id
     * @param string $type
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $slug, $id, $type)
    {
        // Dynamically get the model based on type (Article or Review)
        $model = $this->getModel($type);
        $item = $model::where('slug', $slug)
            ->where('id', $id)
            ->where('is_published', true)
            ->firstOrFail();

        // Check if comments are allowed
        if ($item->allow_comments === false) {
            abort(403, 'Comments are disabled for this item.');
        }

        // Block frozen users (if applicable)
        if (auth()->user()->status === 'frozen') {
            abort(403, 'Your account is frozen.');
        }

        // Validate comment input
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|min:5|max:2000',
        ]);

        if ($validator->fails()) {
            ToastMagic::error($validator->errors()->first('comment'));
            return back()->withInput();
        }

        // Store the comment (polymorphic relationship)
        Comment::create([
            'user_id' => auth()->id(),
            'commentable_id' => $item->id,
            'commentable_type' => $model,
            'body' => $request->comment,
            'is_approved' => true, // Auto-approved for simplicity
        ]);

        // Update the comments count
        $item->increment('comments_count');

        ToastMagic::success('Your comment has been posted.');

        // Redirect to the respective page
        return redirect()->route("comment.post", [
            'slug' => $item->slug,
            'id' => $item->id,
            'type' => $type,
        ]);
    }

    /**
     * Get the model class based on the type (review or article).
     *
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function getModel($type)
    {
        $models = [
            'review' => Review::class,
            'article' => Article::class,
        ];

        return $models[$type] ?? abort(404, 'Invalid type.');
    }

    /**
     * Get popular reviews (dummy data or dynamic).
     *
     * @return array
     */
    private function getPopularReviews()
    {
        return [
            [
                'title' => 'OnePlus 15 review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oneplus-15/-347x151/gsmarena_000.jpg',
                'url' => '/review/oneplus-15',
            ],
            [
                'title' => 'Realme GT 8 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/realme-gt-8-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/realme-gt-8-pro',
            ],
            [
                'title' => 'Oppo Find X9 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oppo-find-x9-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/oppo-find-x9-pro',
            ],
        ];
    }
}

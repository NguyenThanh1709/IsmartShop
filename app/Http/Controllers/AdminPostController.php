<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCat;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class AdminPostController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'post']);
            return $next($request);
        });

        function data_tree($data_src, $parent_id = 0, $level = 0)
        {
            $result = array();
            foreach ($data_src as $item) {
                if ($item['parent_id'] == $parent_id) {
                    $item['level'] = $level;
                    $result[] = $item;
                    $child = data_tree($data_src, $item['id'], $level + 1);
                    $result = array_merge($result, $child);
                }
            }
            return $result;
        }
    }

    public function listPost(Request $request)
    {

        $status = $request->input('status');
        $list_option = [
            'public' => 'Công khai',
            'pending' => 'Chờ duyệt',
            'disable' => 'Thùng rác'
        ];
        if ($status == 'trash') {
            $list_option = [
                'restore' => 'Khôi phục',
                'delete' => 'Xoá vĩnh viễn'
            ];
            $listPost = Post::onlyTrashed()->orderBy('id', 'desc')->paginate(20);
        } else if ($status == 'public') {
            $list_option = [
                'pending' => 'Chờ duyệt',
                'disable' => 'Thùng rác'
            ];
            $listPost = Post::where('status', $status)->orderBy('id', 'desc')->paginate(20);
        } else if ($status == 'pending') {
            $list_option = [
                'public' => 'Công khai',
                'disable' => 'Thùng rác'
            ];
            $listPost = Post::where('status', $status)->orderBy('id', 'desc')->paginate(20);
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $listPost = Post::where('title', 'LIKE', "%{$keyword}%")->orderBy('id', 'desc')->paginate(20);
        }
        $countPostPublic = Post::where('status', 'public')->count();
        $countPostPending = Post::where('status', 'pending')->count();
        $countPostTrash = Post::onlyTrashed()->count();
        $count = [$countPostPublic, $countPostPending, $countPostTrash];
        return view('admin.post.listPost', compact('listPost', 'count', 'status', 'list_option'));
    }

    public function actionPost(Request $request)
    {
        if (Gate::allows('post.edit')) {
            $list_check = $request->input('list_check');
            if ($list_check) {
                if (!empty($list_check)) {
                    $act = $request->input('act');
                    switch ($act) {
                        case "disable":
                            Post::destroy($list_check);
                            return redirect('admin/post/list?status=trash')->with('status', 'Đã chuyển bài viết vào thùng rác');
                            break;
                        case "restore":
                            Post::withTrashed()->whereIn('id', $list_check)->restore();
                            return redirect()->route('index.post')->with('status', 'Đã khôi phục thành công bài viết');
                            break;
                        case "delete":
                            Post::withTrashed()->whereIn('id', $list_check)->forceDelete();
                            return redirect('admin/post/list?status=trash')->with('status', 'Bạn đã xoá bài viết vĩnh viễn');
                            break;
                        case "public":
                            Post::whereIn('id', $list_check)->update(['status' => 'public']);
                            return redirect('admin/post/list?status=public')->with('status', 'Đã cập nhật thông tin bài viết thành công');
                            break;
                        case "pending":
                            Post::whereIn('id', $list_check)->update(['status' => 'pending']);
                            return redirect('admin/post/list?status=pending')->with('status', 'Đã cập nhật thông tin bài viết thành công');
                            break;
                    }
                }
                return redirect()->route('index.post')->with('status-danger', 'Bạn không thể thao tác trên tài khoản của bạn');
            } else {
                return redirect()->route('index.post')->with('status-danger', 'Bạn cần click chọn đối tượng để thao tác');
            }
        } else {
            return redirect()->route('index.post')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function addPost()
    {
        if (Gate::allows('post.add')) {
            $list_cat = PostCat::all();
            $list_cat_tree = data_tree($list_cat, 0, 0);
            return view('admin.post.addPost', compact('list_cat_tree'));
        } else {
            return redirect()->route('index.post')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function store(Request $request)
    {
        if (Gate::allows('post.add')) {
            $request->validate(
                [
                    'name' => 'required',
                    'slug' => 'required',
                    'status' => 'required|in:public,pending',
                    'file' => ['image', 'mimes:jpeg,png,webp,jpg,gif,svg', 'max:2048'],
                    'content' => 'required',
                    'cat' => 'required'
                ],
                [
                    'required' => 'Không được để trống trường :attribute',
                    'in' => 'Vui lòng chọn trạng thái',
                    'max:2048' => 'Kích thước file không được vượt quá 2MB',
                    'image' => 'File tải lên phải là file ảnh',
                    'mimes' => 'Định dạng file không hợp lệ, file phải có đuôi jpeg,png,png,jpg',
                ],
                [
                    'name' => 'Tên danh mục',
                    'status' => 'Trạng thái',
                    'file' => 'Hình ảnh',
                    'content' => 'Nội dung',
                    'thumbnail' => 'Ảnh nổi bật',
                    'cat' => 'Danh mục'
                ]
            );
            if ($request->hasFile('file')) {
                $file = $request->file;
                $filename = $file->getClientOriginalName();
                $file->move('public/uploads', $file->getClientOriginalName());
                $thumbnail = 'public/uploads/' . $filename;
            }
            Post::create(
                [
                    'title' => $request->input('name'),
                    'slug' => $request->input('slug'),
                    'thumbnail' => $thumbnail,
                    'content' => $request->input('content'),
                    'user_id' => Auth::user()->id,
                    'post_cat_id' => $request->input('cat'),
                    'status' => $request->input('status')
                ]
            );
            return redirect()->route('index.post')->with('status', 'Đã thêm bài viết mới thành công');
        } else {
            return redirect()->route('index.post')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function editPost($id)
    {
        if (Gate::allows('post.edit')) {
            $post = Post::find($id);
            $status = $post->status;
            $list_cat = PostCat::all();
            $list_cat_tree = data_tree($list_cat, 0, 0);
            return view('admin.post.editPost', compact('post', 'list_cat_tree', 'status'));
        } else {
            return redirect()->route('index.post')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function updatePost(Request $request, $id)
    {
        if (Gate::allows('post.edit')) {
            $request->validate(
                [
                    'name' => 'required',
                    'slug' => 'required',
                    'status' => 'required|in:public,pending',
                    'content' => 'required',
                ],
                [
                    'required' => 'Không được để trống trường :attribute',
                    'in' => 'Vui lòng chọn trạng thái'
                ],
                [
                    'name' => 'Tên danh mục',
                    'status' => 'Trạng thái',
                    'file' => 'Hình ảnh',
                    'content' => 'Nội dung',
                ]
            );
            if ($request->hasFile('file')) {
                $file = $request->file;
                $filename = $file->getClientOriginalName();
                $filename = $file->getClientOriginalName();
                $file->move('public/uploads', $file->getClientOriginalName());
                $thumbnail = 'public/uploads/' . $filename;
                Post::find($id)->update([
                    'title' => $request->input('name'),
                    'slug' => $request->input('slug'),
                    'thumbnail' => $thumbnail,
                    'content' => $request->input('content'),
                    'user_id' => Auth::user()->id,
                    'post_cat_id' => $request->input('cat'),
                    'status' => $request->input('status')
                ]);
            } else {
                Post::find($id)->update([
                    'title' => $request->input('name'),
                    'slug' => $request->input('slug'),
                    'content' => $request->input('content'),
                    'user_id' => Auth::user()->id,
                    'post_cat_id' => $request->input('cat'),
                    'status' => $request->input('status')
                ]);
            }
            return Redirect::back()->with('status', 'Đã cập nhật dữ liệu mới thành công');
        } else {
            return redirect()->route('index.post')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function deletePost($id)
    {
        if (Gate::allows('post.delete')) {
            $post = Post::find($id);
            $post->Delete();
            return redirect()->route('index.post')->with('status', 'Đã xoá bài viết thành công');
        } else {
            return redirect()->route('index.post')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }
    //Cat
    public function listCat()
    {
        $list_cat = PostCat::all();
        $list_cat_tree = data_tree($list_cat, 0, 0);
        $list_cat_tree_page = data_tree($list_cat, 0, 0);
        return view('admin.post.listCat', compact('list_cat', 'list_cat_tree', 'list_cat_tree_page'));
    }

    public function deleteCat($id)
    {
        if (Gate::allows('post.cat.delete')) {
            $postCat = PostCat::find($id);
            $postCat->delete();
            return redirect()->route('cat.list')->with('status', 'Đã xoá danh mục bài viết thành công');
        } else {
            return Redirect::back()->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function addPostCat(Request $request)
    {
        if (Gate::allows('post.cat.add')) {
            $request->validate(
                [
                    'name' => 'required',
                    'slug' => 'required',
                    'status' => 'required|in:public,pending'
                ],
                [
                    'required' => 'Không được để trống trường :attribute',
                    'in' => 'Vui lòng chọn trạng thái'
                ],
                [
                    'name' => 'Tên danh mục',
                    'status' => 'Trạng thái'
                ]
            );
            PostCat::create([
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'user_id' => Auth::user()->id,
                'parent_id' => $request->input('cat'),
                'status' => $request->input('status')
            ]);
            return Redirect::back()->with('status', 'Thêm dữ liệu mới thành công');
        } else {
            return redirect()->route('index.post')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function editCat(Request $request, $id)
    {
        $cat = PostCat::find($id);
        $user = $cat->user->name;
        return response()->json(['data' => $cat, 'user' => $user], 200);
    }

    public function updateCat(Request $request, $id)
    {
        if (Gate::allows('post.cat.edit')) {
            $cat = PostCat::find($id);
            if ($cat->id != $request->input('cat')) {
                $cat = PostCat::find($id)->update([
                    'name' => $request->input('name'),
                    'slug' => $request->input('slug'),
                    'status' => $request->input('status'),
                    'parent_id' => $request->input('cat'),
                ]);
            } else {
                $cat = PostCat::find($id)->update([
                    'name' => $request->input('name'),
                    'slug' => $request->input('slug'),
                    'status' => $request->input('status'),
                ]);
            }
            $request->session()->flash('status', 'Cập nhật dữ liệu mới thành công');
            return response()->json(['data' => 'success'], 200);
        } else {
            $request->session()->flash('status-danger', 'Bạn không có quyền truy cập chức năng này');
            return response()->json(['data' => 'success'], 200);
        }
    }
}

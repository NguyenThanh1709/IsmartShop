<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class AdminPageController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'page']);
            return $next($request);
        });
    }

    public function listPage(Request $request)
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
            $listPage = Page::onlyTrashed()->orderBy('id', 'desc')->paginate(20);
        } else if ($status == 'public') {
            $list_option = [
                'pending' => 'Chờ duyệt',
                'disable' => 'Thùng rác'
            ];
            $listPage = Page::where('status', $status)->orderBy('id', 'desc')->paginate(20);
        } else if ($status == 'pending') {
            $list_option = [
                'public' => 'Công khai',
                'disable' => 'Thùng rác'
            ];
            $listPage = Page::where('status', $status)->orderBy('id', 'desc')->paginate(20);
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $listPage = Page::where('name', 'LIKE', "%{$keyword}%")->orderBy('id', 'desc')->paginate(20);
        }
        $countPagePublic = Page::where('status', 'public')->count();
        $countPagePending = Page::where('status', 'pending')->count();
        $countPageTrash = Page::onlyTrashed()->count();
        $count = [$countPagePublic, $countPagePending, $countPageTrash];
        return view('admin.page.listPage', compact('listPage', 'count', 'status', 'list_option'));
    }

    public function actionPage(Request $request)
    {
        if (Gate::allows('page.edit')) {
            $list_check = $request->input('list_check');
            if ($list_check) {
                if (!empty($list_check)) {
                    $act = $request->input('act');
                    switch ($act) {
                        case "disable":
                            Page::destroy($list_check);
                            return redirect('admin/page/list?status=trash')->with('status', 'Đã chuyển dữ liệu vào thùng rác thùng công');
                            break;
                        case "restore":
                            Page::withTrashed()->whereIn('id', $list_check)->restore();
                            return redirect()->route('index.page')->with('status', 'Đã khôi phục thành công dữ liệu');
                            break;
                        case "delete":
                            Page::withTrashed()->whereIn('id', $list_check)->forceDelete();
                            return redirect('admin/page/list?status=trash')->with('status', 'Bạn đã xoá dữ liệu vĩnh viễn');
                            break;
                        case "public":
                            Page::whereIn('id', $list_check)->update(['status' => 'public']);
                            return redirect('admin/page/list?status=public')->with('status', 'Đã cập nhật thông tin dữ liệu thành công');
                            break;
                        case "pending":
                            Page::whereIn('id', $list_check)->update(['status' => 'pending']);
                            return redirect('admin/page/list?status=pending')->with('status', 'Đã cập nhật thông tin dữ liệu thành công');
                            break;
                    }
                }
                return redirect()->route('index.page')->with('status-danger', 'Bạn không thể thao tác trên tài khoản của bạn');
            } else {
                return redirect()->route('index.page')->with('status-danger', 'Bạn cần click chọn đối tượng để thao tác');
            }
        } else {
            return redirect()->route('index.page')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function addPage()
    {
        if (Gate::allows('page.add')) {
            return view('admin.page.addPage');
        } else {
            return redirect()->route('index.page')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function store(Request $request)
    {
        if (Gate::allows('page.add')) {
            $validated = $request->validate(
                [
                    'name' => 'required|max:50',
                    'content' => 'required',
                    'title' => 'required|max:100'
                ],
                [
                    'required' => 'Không được để trống trường :attribute',
                    'max:100' => 'Không được nhập trên 100 ký tự'
                ],
                [
                    'name' => 'Tên trang',
                    'content' => 'Nội dung',
                    'title' => 'Tiêu đề'
                ]
            );
            Page::create([
                'name' => $request->input('name'),
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'status' => $request->input('status'),
                'user_id' => Auth::user()->id,
            ]);
            return redirect()->route('index.page')->with('status', 'Đã thêm trang mới thành công');
        } else {
            return redirect()->route('index.page')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function editPage($id)
    {
        if (Gate::allows('page.edit')) {
            $page = Page::find($id);
            return view('admin.page.editPage', compact('page'));
        } else {
            return redirect()->route('index.page')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function deletePage($id)
    {
        if (Gate::allows('page.delete')) {
            $page = Page::find($id);
            $page->delete();
            return redirect()->route('index.page')->with('status', 'Đã xoá dữ liệu thành công');
        } else {
            return redirect()->route('index.page')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function updatePage(Request $request, $id)
    {
        if (Gate::allows('page.edit')) {
            $validated = $request->validate(
                [
                    'name' => 'required|max:100',
                    'content' => 'required',
                    'title' => 'required'
                ],
                [
                    'required' => 'Không được để trống trường :attribute',
                    'max' => 'Không được nhập trên 100 ký tự'
                ],
                [
                    'name' => 'Tên trang',
                    'content' => 'Nội dung',
                    'title' => 'Tiêu đề'
                ]
            );
            //    return $request->input('name');
            Page::find($id)->update([
                'name' => $request->input('name'),
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'status' => $request->input('status'),
            ]);
            return Redirect::back()->with('status', 'Đã thêm danh mục bài viết mới thành công');
        } else {
            return redirect()->route('index.page')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }
}

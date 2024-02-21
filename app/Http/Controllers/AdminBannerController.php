<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class AdminBannerController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'banner']);
            return $next($request);
        });
    }

    public function listBanner(Request $request)
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
            $listBanner = Banner::onlyTrashed()->orderBy('id', 'desc')->paginate(20);
        } else if ($status == 'public') {
            $list_option = [
                'pending' => 'Chờ duyệt',
                'disable' => 'Thùng rác'
            ];
            $listBanner = Banner::where('status', $status)->orderBy('id', 'desc')->paginate(20);
        } else if ($status == 'pending') {
            $list_option = [
                'public' => 'Công khai',
                'disable' => 'Thùng rác'
            ];
            $listBanner = Banner::where('status', $status)->orderBy('id', 'desc')->paginate(20);
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $listBanner = Banner::where('name', 'LIKE', "%{$keyword}%")->orderBy('id', 'desc')->paginate(20);
        }
        $countbannerPublic = Banner::where('status', 'public')->count();
        $countbannerPending = Banner::where('status', 'pending')->count();
        $countbannerTrash = Banner::onlyTrashed()->count();
        $count = [$countbannerPublic, $countbannerPending, $countbannerTrash];
        return view('admin.banner.listBanner', compact('listBanner', 'count', 'status', 'list_option'));
    }

    public function actionBanner(Request $request)
    {
        if (Gate::allows('banner.edit')) {
            $list_check = $request->input('list_check');
            if ($list_check) {
                if (!empty($list_check)) {
                    $act = $request->input('act');
                    switch ($act) {
                        case "disable":
                            Banner::destroy($list_check);
                            return redirect('admin/banner/list?status=trash')->with('status', 'Đã chuyển banner vào thùng rác');
                            break;
                        case "restore":
                            Banner::withTrashed()->whereIn('id', $list_check)->restore();
                            return redirect()->route('index.banner')->with('status', 'Đã khôi phục thành công banner');
                            break;
                        case "delete":
                            Banner::withTrashed()->whereIn('id', $list_check)->forceDelete();
                            return redirect('admin/banner/list?status=trash')->with('status', 'Bạn đã xoá banner vĩnh viễn');
                            break;
                        case "public":
                            Banner::whereIn('id', $list_check)->update(['status' => 'public']);
                            return redirect('admin/banner/list?status=public')->with('status', 'Đã cập nhật thông tin banner thành công');
                            break;
                        case "pending":
                            Banner::whereIn('id', $list_check)->update(['status' => 'pending']);
                            return redirect('admin/banner/list?status=pending')->with('status', 'Đã cập nhật thông tin banner thành công');
                            break;
                    }
                }
            } else {
                return redirect()->route('index.banner')->with('status-danger', 'Bạn cần click chọn đối tượng để thao tác');
            }
        } else {
            return redirect()->route('index.banner')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function addBanner()
    {
        if (Gate::allows('banner.add')) {
            return view('admin.banner.addBanner');
        } else {
            return redirect()->route('index.banner')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function store(Request $request)
    {
        if (Gate::allows('banner.add')) {
            $request->validate(
                [
                    'name' => 'required|max:100',
                    'slug' => 'required',
                    'link' => 'required',
                    'content' => 'required',
                    'status' => 'required|in:public,pending',
                    'file' => ['image', 'mimes:jpeg,png,webp,jpg,gif,svg', 'max:2048']
                ],
                [
                    'required' => 'Không được để trống trường :attribute',
                    'max:100' => 'Độ dài chuổi không lớn hơn 100 ký tự',
                    'max:2048' => 'Kích thước file không được vượt quá 2MB',
                    'image' => 'File tải lên phải là file ảnh',
                    'mimes' => 'Định dạng file không hợp lệ, file phải có đuôi jpeg,png,png,jpg'
                ],
                [
                    'name' => 'Tên banner',
                    'slug' => 'Slug',
                    'link' => 'Link liên kết',
                    'content' => 'Nội dung',
                    'status' => 'Trạng thái',
                ]
            );
            if ($request->hasFile('file')) {
                $file = $request->file;
                $filename = $file->getClientOriginalName();
                $file->move('public/uploads', $file->getClientOriginalName());
                $thumbnail = 'public/uploads/' . $filename;
            }
            Banner::create(
                [
                    'name' => $request->input('name'),
                    'slug' => $request->input('slug'),
                    'link' => $request->input('link'),
                    'thumbnail' => $thumbnail,
                    'content' => $request->input('content'),
                    'user_id' => Auth::user()->id,
                    'status' => $request->input('status')
                ]
            );
            return redirect()->route('index.banner')->with('status', 'Đã thêm banner mới thành công');
        } else {
            return redirect()->route('index.banner')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function editBanner($id)
    {
        if (Gate::allows('banner.edit')) {
            $banner = Banner::find($id);
            return view('admin.banner.editBanner', compact('banner'));
        } else {
            return redirect()->route('index.banner')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function deleteBanner($id)
    {
        if (Gate::allows('banner.delete')) {
            Banner::find($id)->delete();
            return Redirect::back()->with('status', 'Đã xoá banner thành công');
        } else {
            return redirect()->route('index.banner')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function updateBanner(Request $request, $id)
    {
        if (Gate::allows('banner.edit')) {
            $request->validate(
                [
                    'name' => 'required|max:100',
                    'slug' => 'required',
                    'link' => 'required',
                    'content' => 'required',
                    'status' => 'required|in:public,pending',
                    'file' => ['image', 'mimes:jpeg,png,webp,jpg,gif,svg', 'max:2048']
                ],
                [
                    'required' => 'Không được để trống trường :attribute',
                    'max:100' => 'Độ dài chuổi không lớn hơn 100 ký tự',
                    'max:2048' => 'Kích thước file không được vượt quá 2MB',
                    'image' => 'File tải lên phải là file ảnh',
                    'mimes' => 'Định dạng file không hợp lệ, file phải có đuôi jpeg,png,png,jpg'
                ],
                [
                    'name' => 'Tên banner',
                    'slug' => 'Slug',
                    'link' => 'Link liên kết',
                    'content' => 'Nội dung',
                    'status' => 'Trạng thái',
                ]
            );
            if ($request->hasFile('file')) {
                $file = $request->file;
                $filename = $file->getClientOriginalName();
                $file->move('public/uploads', $file->getClientOriginalName());
                $thumbnail = 'public/uploads/' . $filename;
                Banner::find($id)->update(
                    [
                        'name' => $request->input('name'),
                        'slug' => $request->input('slug'),
                        'link' => $request->input('link'),
                        'thumbnail' => $thumbnail,
                        'content' => $request->input('content'),
                        'status' => $request->input('status')
                    ]
                );
            } else {
                Banner::find($id)->update(
                    [
                        'name' => $request->input('name'),
                        'slug' => $request->input('slug'),
                        'link' => $request->input('link'),
                        'content' => $request->input('content'),
                        'status' => $request->input('status')
                    ]
                );
            }
            return Redirect::back()->with('status', 'Đã cập nhật dữ liệu mới thành công');
        } else {
            return redirect()->route('index.banner')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }
}

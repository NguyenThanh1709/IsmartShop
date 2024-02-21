<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Expr\Cast\Object_;
use SebastianBergmann\Type\ObjectType;
use stdClass;

class AdminSliderController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'slider']);
            return $next($request);
        });
    }

    public function listSilder(Request $request)
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
            $listSilder = Slider::onlyTrashed()->orderBy('id', 'desc')->paginate(20);
        } else if ($status == 'public') {
            $list_option = [
                'pending' => 'Chờ duyệt',
                'disable' => 'Thùng rác'
            ];
            $listSilder = Slider::where('status', $status)->orderBy('id', 'desc')->paginate(20);
        } else if ($status == 'pending') {
            $list_option = [
                'public' => 'Công khai',
                'disable' => 'Thùng rác'
            ];
            $listSilder = Slider::where('status', $status)->orderBy('id', 'desc')->paginate(20);
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $listSilder = Slider::where('name', 'LIKE', "%{$keyword}%")->orderBy('id', 'desc')->paginate(20);
        }
        $countSliderPublic = Slider::where('status', 'public')->count();
        $countSliderPending = Slider::where('status', 'pending')->count();
        $countSliderTrash = Slider::onlyTrashed()->count();
        $count = [$countSliderPublic, $countSliderPending, $countSliderTrash];
        return view('admin.slider.listSlider', compact('listSilder', 'count', 'status', 'list_option'));
    }

    public function actionSlider(Request $request)
    {
        if (Gate::allows('slider.edit')) {
            $list_check = $request->input('list_check');
            if ($list_check) {
                if (!empty($list_check)) {
                    $act = $request->input('act');
                    switch ($act) {
                        case "disable":
                            Slider::destroy($list_check);
                            return redirect('admin/slider/list?status=trash')->with('status', 'Đã chuyển slider vào thùng rác');
                            break;
                        case "restore":
                            Slider::withTrashed()->whereIn('id', $list_check)->restore();
                            return redirect()->route('index.slider')->with('status', 'Đã khôi phục thành công slider');
                            break;
                        case "delete":
                            Slider::withTrashed()->whereIn('id', $list_check)->forceDelete();
                            return redirect('admin/slider/list?status=trash')->with('status', 'Bạn đã xoá slider vĩnh viễn');
                            break;
                        case "public":
                            Slider::whereIn('id', $list_check)->update(['status' => 'public']);
                            return redirect('admin/slider/list?status=public')->with('status', 'Đã cập nhật thông tin slider thành công');
                            break;
                        case "pending":
                            Slider::whereIn('id', $list_check)->update(['status' => 'pending']);
                            return redirect('admin/Slider/list?status=pending')->with('status', 'Đã cập nhật thông tin slider thành công');
                            break;
                    }
                }
                return redirect()->route('index.slider')->with('status-danger', 'Bạn không thể thao tác trên tài khoản của bạn');
            } else {
                return redirect()->route('index.slider')->with('status-danger', 'Bạn cần click chọn đối tượng để thao tác');
            }
        } else {
            return redirect()->route('index.slider')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function addSlider()
    {
        if (Gate::allows('slider.add')) {
            return view('admin.slider.addSlider');
        } else {
            return redirect()->route('index.slider')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function store(Request $request)
    {
        if (Gate::allows('slider.add')) {
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
                    'name' => 'Tên slider',
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
            Slider::create(
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
            return redirect()->route('index.slider')->with('status', 'Đã thêm slider mới thành công');
        } else {
            return redirect()->route('index.slider')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function editSlider($id)
    {
        if (Gate::allows('slider.edit')) {
            $slider = Slider::find($id);
            return view('admin.slider.editSlider', compact('slider'));
        } else {
            return redirect()->route('index.slider')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function deleteSlider($id)
    {
        if (Gate::allows('slider.edit')) {
            Slider::find($id)->delete();
            return Redirect::back()->with('status', 'Đã xoá slider thành công');
        } else {
            return redirect()->route('index.slider')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function updateSlider(Request $request, $id)
    {
        if (Gate::allows('slider.edit')) {
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
                    'name' => 'Tên slider',
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
                Slider::find($id)->update(
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
                Slider::find($id)->update(
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
            return redirect()->route('index.slider')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }
}

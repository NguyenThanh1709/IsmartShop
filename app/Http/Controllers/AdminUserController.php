<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;


class AdminUserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'user']);
            return $next($request);
        });
    }

    public function list(Request $request)
    {
        $list_act = [
            'disable' => 'Xoá tạm thời',
        ];
        $status = $request->input('status');
        if ($status == 'trash') {
            $list_act = [
                'restore' => 'Khôi phục',
                'delete' => 'Xoá vĩnh viên'
            ];
            $users = User::onlyTrashed()->orderBy('id', 'desc')->paginate(20);
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $users = User::where('name', 'LIKE', "%{$keyword}%")->orderBy('id', 'desc')->paginate(20); //Tên trường, toán tử, tham số(keyword);
        }
        $count_user_active = User::count();
        $count_user_trashed = User::onlyTrashed()->count();
        $count = [$count_user_active, $count_user_trashed];
        return view('admin.user.list', compact('users', 'status', 'count', 'list_act'));
    }

    public function add()
    {
        if (Gate::allows('user.add')) {
            $list_roles = Role::pluck('name', 'id');
            $list_roles[''] = '---Chọn nhóm quyền---';
            return view('admin.user.add', compact('list_roles'));
        } else {
            return redirect()->route('user.index')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function store(Request $request)
    {
        if (Gate::allows('user.add')) {
            $request->validate(
                [
                    'name' => 'required|string|max:100',
                    'email' => 'required|string|email|max:255|unique:users',
                    'roles' => 'required|integer',
                    'password' => 'required|min:6',
                    'password_confirmation' => 'required|min:6|required_with:password|same:password'
                ],
                [
                    'required' => 'Không được để trống trường :attribute',
                    'min' => ':attribute có độ dài ít nhất :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'same' => 'Xác nhận mật khẩu không đúng',
                    'integer' => 'Vui lòng chọn quyền',
                ],
                [
                    'roles' => 'Quyền',
                    'name' => 'Tên người dùng',
                    'email' => 'Email',
                    'password' => 'Mật khẩu',
                    'password_confirmation' => 'Xác thực mật khẩu'
                ]
            );
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);
            // return $user;
            $user->roles()->attach($request->input('roles'));

            return redirect('admin/user/list')->with('status', 'Đã thêm thành viên mới thành công!');
        } else {
            return redirect()->route('user.index')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function delete($id)
    {
        if (Gate::allows('user.delete')) {
            if (Auth::id() != $id) {
                $user = User::find($id);
                $user->delete();
                return Redirect::back()->with('status', 'Đã xoá thành viên thành công !');
            } else {
                return redirect('admin/user/list')->with('status', 'Bạn không thể xoá mình ra khỏi hệ thống !');
            }
        } else {
            return redirect()->route('user.index')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function edit(User $user)
    {
        if (Gate::allows('user.edit')) {
            $selectOption = $user->roles->pluck('id')->toArray();
            $list_roles = Role::pluck('name', 'id');
            return view('admin.user.edit', compact('selectOption', 'user', 'list_roles'));
        } else {
            return redirect()->route('user.index')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function update(Request $request, User $user)
    {
        if (Gate::allows('user.edit')) {
            $request->validate(
                [
                    'name' => 'required|string|max:100',
                    'email' => 'required|email',
                    // 'role' => 'required|integer',
                ],
                [
                    'required' => 'Không được để trống trường :attribute',
                    'min' => ':attribute có độ dài ít nhất :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'integer' => 'Vui lòng chọn quyền',
                ],
                [
                    // 'roles' => 'Quyền',
                    'name' => 'Tên người dùng',
                ]
            );

            $user->update(['name' => $request->input('name'), 'email' => $request->input('email')]);

            $user->roles()->sync($request->input('roles'));

            return redirect('admin/user/list')->with('status', 'Cập nhật thành công thông tin người dùng: ' . $request->input('name'));
        } else {
            return redirect()->route('user.index')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function change_password(Request $request, $id)
    {
        $request->validate(
            [
                'password_new' => 'required|min:6',
                'password_confirmation_new' => 'required|min:6|required_with:password_new|same:password_new'
            ],
            [
                'required' => 'Không được để trống trường :attribute',
                'min' => ':attribute có độ dài ít nhất :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'same' => 'Xác nhận mật khẩu không đúng',
            ],
            [
                'password_new' => 'Mật khẩu mới',
                'password_confirmation_new' => 'Xác nhận mật khẩu mới',
            ]
        );
        $user = User::find($id);
        User::find($id)->update(['password' => Hash::make($request->input('password_new'))]);
        return redirect('admin/user/list')->with('status', 'Thay đổi thành công mật khẩu người dùng: ' . $user->name);
    }

    public function action(Request $request)
    {
        if (Gate::allows('user.edit')) {
            $list_check = $request->input('list_check');
            if ($list_check) {
                foreach ($list_check as $k => $v) {
                    if (Auth::id() == $v) {
                        unset($list_check[$k]);
                    }
                }
                if (!empty($list_check)) {
                    $act = $request->input('act');
                    if ($act == 'disable') {
                        User::destroy($list_check);
                        return redirect()->route('user.index')->with('status', 'Vô hiệu hoá thành công tài khoản');
                    }
                    if ($act == 'restore') {
                        User::withTrashed()->whereIn('id', $list_check)->restore();
                        return redirect()->route('user.index')->with('status', 'Bạn đã khôi phục thành công tài khoản');
                    }
                    if ($act == 'delete') {
                        User::withTrashed()->whereIn('id', $list_check)->forceDelete();
                        return redirect('admin/user/list?status=trash')->with('status', 'Bạn đã xoá user vĩnh viễn');
                    }
                }
                return redirect()->route('user.index')->with('status-danger', 'Bạn không thể thao tác trên tài khoản của bạn');
            } else {
                return redirect()->route('user.index')->with('status-danger', 'Bạn cần click chọn đối tượng để thao tác');
            }
        } else {
            return redirect()->route('user.index')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }
}

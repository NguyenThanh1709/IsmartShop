<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class AdminRoleController extends Controller
{
    //
    public function show(Request $request)
    {
        $list_option = [
            'delete' => 'Xoá vĩnh viễn'
        ];
        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
            $listRole = Role::where('name', 'LIKE', "%{$keyword}%")->orderBy('id', 'desc')->paginate(20);
        } else {
            $listRole = Role::paginate(20);
        }
        return view('admin.role.listRole', compact('listRole', 'list_option'));
    }

    public function add()
    {
        if (Gate::allows('role.add')) {
            $permissions = Permission::all()->groupBy(function ($permission) {
                return explode('.', $permission->slug)[0];
            });
            return view('admin.role.addRole', compact('permissions'));
        } else {
            return redirect()->route('role.list')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function store(Request $request)
    {
        if (Gate::allows('role.add')) {
            $request->validate(
                [
                    'name' => 'required|unique:roles,name',
                    'description' => 'required',
                    'permission_id' => 'nullable|array',
                    'permission_id.*' => 'exists:permissions,id'
                ],
                [
                    'required' => 'Không được để trống',
                    'unique' => 'Trường :attribute đã tồn tại trong hệ thống'
                ],
                [
                    'name' => 'Tên vai trò',
                    'description' => 'môt tả vai trò'
                ]
            );

            $role = Role::create([
                'name' => $request->input('name'),
                'description' => $request->input('description')
            ]);

            // Insert list permission
            $role->permissions()->attach($request->input('permission_id'));

            return redirect()->route('role.list')->with('status', 'Đã thêm vai trò mới thành công');
        } else {
            return redirect()->route('role.list')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function delete($id)
    {
        if (Gate::allows('role.delete')) {
            Role::find($id)->delete();
            return Redirect::back()->with('status', 'Đã xoá vai trò thành công');
        } else {
            return redirect()->route('role.list')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function deleteMultiple(Request $request)
    {
        if (Gate::allows('role.delete')) {
            $list_check = $request->input('list_check');
            Role::whereIn('id', $list_check)->delete();
            return Redirect::back()->with('status', 'Đã xoá vai trò thành công');
        } else {
            return redirect()->route('role.list')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function edit(Role $role)
    {
        if (Gate::allows('role.edit')) {
            $permissions = Permission::all()->groupBy(function ($permission) {
                return explode('.', $permission->slug)[0];
            });
            return view('admin.role.editRole', compact('permissions', 'role'));
        } else {
            return redirect()->route('role.list')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function update(Request $request, Role $role)
    {
        if (Gate::allows('role.edit')) {
            $request->validate(
                [
                    'name' => 'required|unique:roles,name,' . $role->id,
                    'description' => 'required',
                    'permission_id' => 'nullable|array',
                    'permission_id.*' => 'exists:permissions,id',
                ],
                [
                    'required' => 'Không được để trống',
                    'unique' => 'Trường :attribute đã tồn tại trong hệ thống'
                ],
                [
                    'name' => 'Tên vai trò',
                    'description' => 'môt tả vai trò'
                ]
            );

            $role->permissions()->sync($request->input('permission_id', []));

            return redirect()->route('role.list')->with('status', 'Cập nhật thành công thông tin vai trò');
        } else {
            return redirect()->route('role.list')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Nette\Utils\Json;

class AdminPermissionController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'permission']);
            return $next($request);
        });
    }
    public function add()
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->slug)[0];
        });

        return view('admin.permission.addPermission', compact('permissions'));
    }

    public function store(Request $request)
    {
        if (Gate::allows('permission.add')) {
            $request->validate(
                [
                    'name' => 'required|max:255',
                    'slug' => 'required'
                ],
                [
                    'required' => 'Không được để trống trường :attribute',
                    'max' => 'Không được vượt quá 255 ký tự :attribute'
                ],
                [
                    'name' => 'Tên quyền'
                ]
            );
            Permission::create([
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'description' => $request->input('description'),
            ]);

            return Redirect::back()->with('status', 'Đã thêm quyền mới thành công');
        } else {
            return redirect()->route('permission.add')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function edit($id)
    {
        $permission = Permission::find($id);
        return json_encode($permission);
    }

    public function update(Request $request)
    {
        if (Gate::allows('permission.edit')) {
            $id = $request->input('id');
            Permission::find($id)->update([
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'description' => $request->input('description'),
            ]);
            $request->session()->flash('status', 'Cập nhật dữ liệu mới thành công');
            return response()->json('success', 200);
        } else {
            $request->session()->flash('status-danger', 'Bạn không có quyền truy cập chức năng này');
            return response()->json('success', 200);
        }
    }

    public function delete($id)
    {
        if (Gate::allows('permission.delete')) {
            Permission::find($id)->delete();
            return Redirect::back()->with('status', 'Đã xoá quyền thành công');
        } else {
            return redirect()->route('permission.add')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }
}

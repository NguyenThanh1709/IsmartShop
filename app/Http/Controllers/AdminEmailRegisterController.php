<?php

namespace App\Http\Controllers;

use App\Mail\SendMailRegister;
use App\Models\RegisterNoti;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;


class AdminEmailRegisterController extends Controller
{
    //
    public function index(): View
    {
        $listEmail = RegisterNoti::paginate(20);
        return view('admin.registerNoti.list', compact('listEmail'));
    }

    public function delete($id)
    {
        $record = RegisterNoti::find($id);
        if ($record->delete()) {
            return Redirect::back()->with('status', 'Đã xoá dữ liệu thành công');
        } else {
            return Redirect::back()->with('status-danger', 'Lỗi không thể xoá dữ liệu');
        }
    }

    public function sendEmail()
    {
        $listEmail = RegisterNoti::all();
        $data = array();
        foreach ($listEmail as $email) {
            $data = [
                'emailName' => $email->email,
            ];
            Mail::to($email->email)->send(new SendMailRegister($data));
        }
        return Redirect::back()->with('status', 'Đã gửi Email thông báo thành công!');
    }
}

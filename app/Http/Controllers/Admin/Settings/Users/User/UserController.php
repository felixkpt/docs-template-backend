<?php

namespace App\Http\Controllers\Admin\Settings\Users\User;

use App\Http\Controllers\Controller;
use App\Mail\SendPassword;
use App\Models\User;
use App\Repositories\SearchRepo;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{

    public function show($id)
    {
        $user = User::with(['roles', 'direct_permissions'])->where(['users.id' => $id]);

        return response(['results' => SearchRepo::of($user, [], [], ['name', 'email', 'roles_multilist', 'direct_permissions_multilist'])->first()]);
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);

        return response(['status' => true, 'results' => SearchRepo::of($user, [], [], ['name', 'email'])]);
    }

    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'roles_list' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    if (count(array_filter($value, fn ($role) => $role !== null)) === 0) {
                        $fail('The roles field must contain at least one role.');
                    }
                },
            ],
            'direct_permissions_list' => 'nullable|array',
        ]);

        $request->merge(['roles' => request()->roles_list]);
        $request->merge(['permissions' => request()->direct_permissions_list]);


        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password') ? bcrypt($request->input('password')) : $user->password,
        ]);

        if ($request->roles) {

            $roles = Role::whereIn('id', $request->input('roles'))->get();
            $user->syncRoles($roles);
        }

        if ($request->permissions) {

            $permissions = Permission::whereIn('id', $request->input('permissions'))->get();
            $user->syncPermissions($permissions);
        }

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    function profileShow() {
        
    }

    public function profileUpdate(Request $request)
    {

        $user = User::find(auth()->user()->id);
        $request->validate([
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {
            $datePath = Carbon::now()->format('Y/m/d');
            $avatarPath = $request->file('avatar')->store('users/' . $datePath);
            $user->avatar = $avatarPath;
        }

        $user->save();

        $user = User::find(auth()->user()->id);
        $user->token = $user->createToken("API TOKEN")->plainTextToken;

        $roles = $user->getRoleNames();
        $user->roles = $roles;
        return response(['type' => 'success', 'results' => $user, 'message' => 'User updated Successfully']);
    }

    public function updateSelfPassword()
    {

        request()->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|max:100|confirmed',
        ]);

        $user = User::find(auth()->user()->id);

        $user->password = Hash::make(request('password'));
        $user->update();
        $password = request('password');

        $data = [
            'subject' => 'New Password For ' . config('app.name'),
            'message' => 'Your ' . config('app.name') . ' new password is a below',
            'password' => $password,
            'instruction' => 'Please use the password as it appears.',
            'user_name' => $user->name,
            'user_email' => $user->email,
        ];
        try {
            Mail::to($user->email)->send(new SendPassword($data));
        } catch (\Exception $e) {

            return response(['type' => 'error', 'message' => $e->getMessage()], 500);
        }

        return response(['type' => 'success', 'message' => 'Password updated Successfully']);
    }

    public function updateOthersPassword()
    {

        request()->validate([
            'password' => 'required|string|min:8|max:100|confirmed',
        ]);

        $user = User::findOrFail(request()->user_id);

        $user->password = Hash::make(request('password'));
        $user->update();
        $password = request('password');

        $data = [
            'subject' => 'New Password For ' . config('app.name'),
            'message' => 'Your ' . config('app.name') . ' new password is a below',
            'password' => $password,
            'instruction' => 'Please use the password as it appears.',
            'user_name' => $user->name,
            'user_email' => $user->email,
        ];
        try {
            Mail::to($user->email)->send(new SendPassword($data));
        } catch (\Exception $e) {

            return response(['type' => 'error', 'message' => $e->getMessage()], 500);
        }

        return response(['type' => 'success', 'message' => 'Password updated Successfully']);
    }

    public function loginUser($userId)
    {

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->token = $user->createToken("API TOKEN")->plainTextToken;

        $roles = $user->getRoleNames();
        $user->roles = $roles;

        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'results' => $user,
        ], 200);
    }

    public function listAttemptedLogins()
    {
        $days = \request()->days ?? 30;
        $failedloginattempts = AuthenticationLog::leftjoin('users', 'authentication_log.authenticatable_id', 'users.id')
            ->select(
                'authentication_log.id',
                'authentication_log.authenticatable_type',
                'authentication_log.ip_address',
                'authentication_log.user_agent',
                'authentication_log.login_at as time_of_access',
                'authentication_log.logout_at',
                'authentication_log.login_successful as successful',
                'users.name as user'
            )->where('login_successful', '=', 0)->whereDate('authentication_log.login_at', '>=', Carbon::today()->subDays($days));



        if (\request()->tabs) {
            return [
                'failed_login_attempts' => $failedloginattempts->count()
            ];
        }



        $authUserEmail = auth()->user()->email;

        return SearchRepo::of($failedloginattempts)
            ->addColumn('login_successful', function ($failedloginattempts) {
                if ($failedloginattempts->login_successful) {
                    $color = 'success';
                } else {
                    $color = 'danger';
                }
                return '<a href="javascript:void(0)"  class="btn badge btn-outline-' . $color . ' btn-sm"> ' . StatusRepository::getFailedLogin($failedloginattempts->login_successful) . '</a>';
            })


            ->paginate();
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}

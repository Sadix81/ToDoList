<?php

namespace App\Repositories\Profile;

class ProfileRepository implements ProfileRepositoryInterface{
    
    public function update($user , $request){

        if(request()->hasFile('image')){
            $image_name = time() . '-' . $request->title . '-' . $request->image->getClientOriginalName();
            $request->image->move(public_path('images') , $image_name);
        }

       try {
        $user->update([
            'fullname' => $request->fullname ? $request->fullname : $user->fullname,
            'username' => $request->username ? $request->username : $user->username,
            'email' => $request->email ? $request->email : $user->email,
            'mobile' => $request->mobile ? $request->mobile : $user->mobile,
            'avatar' => $request->avatar ? $image_name : null,
            'password' => $request->password ? $request->password : $user->password,
        ]);
       } catch (\Throwable $th) {
        throw $th;
       }
    }
}
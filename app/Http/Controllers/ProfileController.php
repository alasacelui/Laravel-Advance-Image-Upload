<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Avatar;
use App\Models\TmpFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProfileController extends Controller
{
    public function index()
    {
        /**
         * get the associated images from the authenticated user
         * @return collection $profile_pictures
         */
        $profile_pictures = auth()->user()->getMedia('avatars');

        return view('home', compact('profile_pictures'));
    }

    public function store(Request $request)
    {
        /**
         * authenticated user
         */
        $user = auth()->user(); 
        
        /**
         * move the image from its temporary folder 
         * (tmp) to the new spatie media storage 
         */

        $user->addMedia(storage_path('app/public/tmp/'. $request->profile)) 
            //->preservingOriginal() // If you want to not move, but copy, the original file
             ->toMediaCollection('avatars');

        /**
         * after img upload
         * remove the image model by the requested->profile (name)
         */
             TmpFile::where('file_name', $request->profile)->delete();


        return back()->with('message' , 'Image Profile Added Successfully');
        
    }

    public function temporary_upload(Request $request)
    {
        if($request->hasFile('profile'))
        {
            $img = $request->file('profile');

            $img_name = $img->hashName(); // get the hashed name of the img file (unique)

            $img->storeAs('tmp', $img_name, 'public'); // store temporarily on the default disk (storage > public)

            TmpFile::create(['file_name' => $img_name]);

            return $img_name;
        }

        return ''; 
    }

    /**
     * Undo file upload
     */
    public function revert(Request $request) 
    {
    
        $data = $request->getContent();

        Storage::disk('public')->delete("tmp/$data");
        TmpFile::where('file_name', $data)->delete();
       
    }















    public function change_profile(User $user, Request $request)
    {
        $user->update(['avatar_id' => $request->avatar ]);

        return back()->with('message' , ' Profile Updated Added Successfully');
    }


    public function destroy_profile(Media $media)
    {

        $media->delete();

        return back()->with('message' , 'Image Profile Deleted Successfully');
    }
}

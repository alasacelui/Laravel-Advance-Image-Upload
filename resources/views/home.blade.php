@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('message'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @error('profile')
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ $message }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @enderror

                <form action="{{ route('profile.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <input type="file" name="profile" required>
                    <button type="submit" class="btn btn-dark btn-sm form-control">Upload</button>
                </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pictures</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="card-deck">
                                    
                                @foreach ($profile_pictures as $profile)
                                <div class="col">
                                    <div class="card">
                                        
                                        <img src="{{ $profile->getUrl('card') }}" class="card-img-top" alt="...">
                                        <div class="card-body text-center">
                                            <form action="{{ route('profile.change',auth()->id()) }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="avatar" value="{{ $profile->id }}">
                                                <button type="submit" class="btn btn-sm btn-outline-info">Make Profile</button>
                                            </form>
                                            <form action="{{ route('profile.remove', $profile->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger mt-2">Remove</button>
                                            </form>
                                        </div>
                                    
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{--File Pond--}}
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    
    <script>

        FilePond.registerPlugin(FilePondPluginImagePreview);
        // Get a reference to the file input element
        const profile = document.querySelector('input[name="profile"]');

         // Create a FilePond instance
         const pond = FilePond.create(profile, {
            //  instantUpload: false,
             storeAsFile: true,
             acceptedFileTypes: ['image/*'], 
             server:{
                 url: '/profile/tmp_upload',
                headers: {
                 'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                revert: '/revert'
             },
       
         });

    </script>
@endsection
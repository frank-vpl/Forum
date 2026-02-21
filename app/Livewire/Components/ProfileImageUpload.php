<?php

namespace App\Livewire\Components;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileImageUpload extends Component
{
    use WithFileUploads;

    public $image;

    public $previewUrl;

    public $isUploading = false;

    public function mount()
    {
        $this->previewUrl = auth()->user()?->profile_image_url;
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image|max:2048', // 2MB Max
        ]);

        // Automatically save the image after it's been validated and uploaded to temporary storage
        $this->saveImage();
    }

    public function saveImage()
    {
        $user = auth()->user();

        if ($this->image) {
            // Generate unique filename
            $extension = $this->image->getClientOriginalExtension();
            $filename = $user->id.'_'.time().'.'.$extension;

            // Store the image in the profile_images directory under public disk
            $path = $this->image->storeAs('profile_images', $filename, 'public');

            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Update user's profile image
            $user->update(['profile_image' => $path]);

            $this->previewUrl = $user->profile_image_url;
            $this->image = null; // Clear the image property

            session()->flash('message', 'Profile image updated successfully.');
        }
    }

    public function removeImage()
    {
        $user = auth()->user();

        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
            $user->update(['profile_image' => null]);
            $this->previewUrl = null;

            session()->flash('message', 'Profile image removed successfully.');
        }
    }

    public function render()
    {
        return <<<'HTML'
        <div class="mt-6">
            <div class="flex items-center gap-6">
                <div class="relative">
                    @if($previewUrl)
                        <img 
                            src="{{ $previewUrl }}" 
                            alt="Profile Image" 
                            class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700"
                        >
                    @else
                        <div class="bg-gray-200 border-2 border-dashed rounded-full w-24 h-24 flex items-center justify-center text-gray-500">
                            <span class="text-lg">No Image</span>
                        </div>
                    @endif
                </div>
                
                <div class="flex flex-col gap-2">
                    <input 
                        type="file" 
                        wire:model="image" 
                        accept="image/*"
                        class="hidden"
                        id="profile-image-upload"
                    >
                    
                    <label 
                        for="profile-image-upload"
                        class="cursor-pointer px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm"
                    >
                        @if($isUploading)
                            Uploading...
                        @else
                            Upload Image
                        @endif
                    </label>
                    
                    @if($previewUrl)
                        <button 
                            wire:click="removeImage"
                            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm"
                        >
                            Remove Image
                        </button>
                    @endif
                    
                    @error('image') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>
            </div>
            
            @if(session('message'))
                <div class="mt-2 text-green-500 text-sm">
                    {{ session('message') }}
                </div>
            @endif
        </div>
        HTML;
    }
}

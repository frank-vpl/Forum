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
        if (config('auth.require_email_verification') && ! auth()->user()?->hasVerifiedEmail()) {
            return;
        }
        $this->validate([
            'image' => 'image|max:2048', // 2MB Max
        ]);

        // Automatically save the image after it's been validated and uploaded to temporary storage
        $this->saveImage();
    }

    public function saveImage()
    {
        $user = auth()->user();

        if (config('auth.require_email_verification') && ! $user?->hasVerifiedEmail()) {
            return;
        }
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

        if (config('auth.require_email_verification') && ! $user?->hasVerifiedEmail()) {
            return;
        }
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
        <div 
            class="mt-6"
            x-data="{
                uploading: false,
                clientError: '',
                showClientError: false,
                successMsg: '',
                openPicker() { this.$refs.fileInput.click() },
                handleFileChange(e) {
                    const file = e.target.files[0];
                    this.clientError = '';
                    this.showClientError = false;
                    this.successMsg = '';
                    if (!file) return;
                    if (!file.type.startsWith('image/')) {
                        this.clientError = 'Only image files are allowed.';
                        this.showClientError = true;
                        e.target.value = '';
                        setTimeout(() => { this.showClientError = false }, 3000);
                        return;
                    }
                    if (file.size > 2 * 1024 * 1024) {
                        this.clientError = 'Image must be 2MB or less.';
                        this.showClientError = true;
                        e.target.value = '';
                        setTimeout(() => { this.showClientError = false }, 3000);
                        return;
                    }
                    this.uploading = true;
                    $wire.upload(
                        'image',
                        file,
                        () => {
                            this.uploading = false;
                            this.$refs.fileInput.value = '';
                            this.successMsg = 'Image uploaded.';
                            setTimeout(() => { this.successMsg = '' }, 2500);
                        },
                        () => {
                            this.uploading = false;
                            this.$refs.fileInput.value = '';
                            this.clientError = 'Upload failed.';
                            this.showClientError = true;
                            setTimeout(() => { this.showClientError = false }, 3000);
                        }
                    );
                }
            }"
        >
            <div class="flex items-start gap-6">
                <div class="relative group">
                    @if($previewUrl)
                        <div class="relative w-28 h-28 rounded-full ring-4 ring-stone-200 dark:ring-stone-700 overflow-hidden shadow-sm">
                            <img 
                                src="{{ $previewUrl }}" 
                                alt="Profile Image" 
                                class="w-full h-full object-cover"
                            >
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <flux:button
                                    variant="outline"
                                    icon="camera"
                                    size="xs"
                                    @click="openPicker()"
                                >
                                    Change
                                </flux:button>
                            </div>
                        </div>
                    @else
                        <div class="relative w-28 h-28 rounded-full border-2 border-dashed flex items-center justify-center text-gray-500 bg-stone-100 dark:bg-stone-800">
                            <flux:icon.user class="w-10 h-10"/>
                            <div class="absolute bottom-2">
                                <flux:button
                                    variant="primary"
                                    icon="camera"
                                    size="xs"
                                    @click="openPicker()"
                                >
                                    Upload
                                </flux:button>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="flex flex-col gap-3">
                    <input
                        type="file"
                        accept="image/*"
                        class="hidden"
                        id="profile-image-upload"
                        x-ref="fileInput"
                        @change="handleFileChange($event)"
                        :disabled="uploading"
                    >
                    <div class="flex flex-col gap-2">
                        <flux:button
                            variant="primary"
                            icon="camera"
                            class="inline-flex items-center gap-2"
                            x-bind:disabled="uploading"
                            @click="openPicker()"
                        >
                            <span x-show="!uploading">
                                @if($previewUrl)
                                    Replace
                                @else
                                    Upload Image
                                @endif
                            </span>
                            <span class="inline-flex items-center gap-2" x-show="uploading">
                                <flux:icon.loading variant="mini"/>
                                Uploading
                            </span>
                        </flux:button>
                        
                        @if($previewUrl)
                            <flux:button
                                variant="danger"
                                icon="trash"
                                x-bind:disabled="uploading"
                                wire:click="removeImage"
                                wire:loading.attr="disabled"
                                wire:target="removeImage,saveImage"
                                @click="$refs.fileInput.value = ''; uploading = false; clientError = ''; showClientError = false;"
                            >
                                Remove
                            </flux:button>
                        @endif
                    </div>
                    
                    <div x-show="showClientError" class="text-red-500 text-sm" x-text="clientError"></div>
                    <div x-show="successMsg" class="text-green-600 text-sm" x-text="successMsg"></div>
                    
                    @error('image')
                        <div 
                            x-data="{ show: true }"
                            x-show="show"
                            x-init="setTimeout(() => show = false, 3000)"
                            class="text-red-500 text-sm"
                        >
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            
            @if(session('message'))
                <div 
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="mt-2 text-green-500 text-sm"
                >
                    {{ session('message') }}
                </div>
            @endif
        </div>
        HTML;
    }
}

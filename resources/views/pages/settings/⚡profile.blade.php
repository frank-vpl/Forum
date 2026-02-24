<?php

use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Notifications\VerifyEmailChange;
use App\Models\Notification as DbNotification;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    use ProfileValidationRules;

    public string $name = '';
    public string $email = '';
    public ?string $bio = null;
    public ?string $profile_url = null;
    public ?string $profile_link_title = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->bio = Auth::user()->bio;
        $this->profile_url = Auth::user()->profile_url;
        $this->profile_link_title = Auth::user()->profile_link_title;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $originalEmail = $user->email;
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $newEmail = $user->email;
            if (!config('auth.require_email_verification')) {
                $user->email = $newEmail;
                $user->email_verified_at = now();
                $user->pending_email = null;
                $user->pending_email_token = null;
                $user->pending_email_requested_at = null;
            } else {
                $user->email = $originalEmail;
                $token = Str::random(64);
                $user->pending_email = $newEmail;
                $user->pending_email_token = $token;
                $user->pending_email_requested_at = now();
            }
        }

        $user->save();

        if (!config('auth.require_email_verification') && $user->wasChanged('email')) {
            Session::flash('status', 'email-changed');
            Session::flash('status_color', 'green');
        } elseif (! empty($user->pending_email ?? null) && ! empty($user->pending_email_token ?? null)) {
            $url = URL::temporarySignedRoute(
                'email.change.verify',
                now()->addMinutes(60),
                ['id' => $user->getKey(), 'token' => $user->pending_email_token]
            );

            Notification::route('mail', $user->pending_email)
                ->notify(new VerifyEmailChange($url));

            DbNotification::create([
                'user_id' => $user->id,
                'actor_id' => Auth::id(),
                'type' => 'email_change_requested',
                'post_id' => null,
                'comment_id' => null,
            ]);

            Session::flash('status', 'email-change-link-sent');
            Session::flash('status_color', 'yellow');
        }

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function resendPendingEmailChange(): void
    {
        $user = Auth::user();
        if (empty($user->pending_email)) {
            return;
        }
        $user->pending_email_token = Str::random(64);
        $user->pending_email_requested_at = now();
        $user->save();

        $url = URL::temporarySignedRoute(
            'email.change.verify',
            now()->addMinutes(60),
            ['id' => $user->getKey(), 'token' => $user->pending_email_token]
        );

        Notification::route('mail', $user->pending_email)
            ->notify(new VerifyEmailChange($url));

        DbNotification::create([
            'user_id' => $user->id,
            'actor_id' => Auth::id(),
            'type' => 'email_change_requested',
            'post_id' => null,
            'comment_id' => null,
        ]);

        Session::flash('status', 'email-change-link-sent');
    }

    public function cancelPendingEmailChange(): void
    {
        $user = Auth::user();
        if (empty($user->pending_email)) {
            return;
        }
        $user->pending_email = null;
        $user->pending_email_token = null;
        $user->pending_email_requested_at = null;
        $user->save();

        DbNotification::create([
            'user_id' => $user->id,
            'actor_id' => Auth::id(),
            'type' => 'email_change_canceled',
            'post_id' => null,
            'comment_id' => null,
        ]);

        Session::flash('status', 'email-change-canceled');
    }

    #[Computed]
    public function pendingEmail(): ?string
    {
        return Auth::user()->pending_email;
    }

    #[Computed]
    public function pendingEmailMinutesLeft(): ?int
    {
        $at = Auth::user()->pending_email_requested_at;
        if (! $at) {
            return null;
        }
        $elapsed = now()->diffInMinutes($at);
        return max(0, 60 - $elapsed);
    }

    #[Computed]
    public function pendingEmailSecondsLeft(): ?int
    {
        $at = Auth::user()->pending_email_requested_at;
        if (! $at) {
            return null;
        }
        $expiry = $at->copy()->addMinutes(60);
        $left = max(0, $expiry->getTimestamp() - now()->getTimestamp());
        return (int) $left;
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Profile Settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Profile')" :subheading="__('Update your profile information')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <div class="border-b pb-6 border-gray-200 dark:border-gray-700">
                <flux:heading size="md">{{ __('Profile Image') }}</flux:heading>
                <flux:text variant="subtle">{{ __('Manage your avatar') }}</flux:text>
                <div class="mt-4">
                    <livewire:components.profile-image-upload />
                </div>
            </div>
            
            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if ($this->pendingEmail)
                    @php($secLeft = (int) ($this->pendingEmailSecondsLeft ?? 0))
                    @php($expired = $secLeft <= 0)
                    <div id="email-change-card" class="{{ $expired ? 'mt-3 rounded-lg border border-red-600 bg-red-50 p-3 text-red-700 dark:border-red-700 dark:bg-red-900/30 dark:text-red-200' : 'mt-3 rounded-lg border border-yellow-300 bg-yellow-50 p-3 text-yellow-900 dark:border-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-200' }}">
                        <div class="text-sm">
                            <span class="font-medium">Email change pending:</span>
                            <span>{{ $this->pendingEmail }}</span>
                        </div>
                        <div class="mt-1 text-xs opacity-90">
                            Expires in
                            <span id="email-change-countdown" data-seconds="{{ $this->pendingEmailSecondsLeft }}" data-exp="{{ optional(Auth::user()->pending_email_requested_at)?->copy()->addMinutes(60)?->toIso8601String() }}">
                                @php($secLeft = (int) ($this->pendingEmailSecondsLeft ?? 0))
                                @php($minLeft = intdiv($secLeft, 60))
                                @php($secOnly = $secLeft % 60)
                                {{ str_pad((string) $minLeft, 2, '0', STR_PAD_LEFT) }}:{{ str_pad((string) $secOnly, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                        <div id="email-change-note" class="mt-1 text-xs opacity-90">
                            @if($expired)
                                Link expired. Resend to get a new verification link or cancel.
                            @else
                                You can resend a new verification link or cancel this change.
                            @endif
                        </div>
                        <div class="mt-2 flex gap-2">
                            <flux:button id="email-change-resend" size="sm" variant="outline" wire:click="resendPendingEmailChange">Resend</flux:button>
                            <flux:button id="email-change-cancel" size="sm" variant="ghost" wire:click="cancelPendingEmailChange">Cancel</flux:button>
                        </div>
                        <script>
                            (function(){
                                var el = document.getElementById('email-change-countdown');
                                if (!el || el._bound) return;
                                el._bound = true;
                                function calcLeft(){
                                    var exp = el.dataset.exp;
                                    if (!exp) return parseInt(el.dataset.seconds || '0', 10);
                                    var msLeft = (new Date(exp)).getTime() - Date.now();
                                    return Math.max(0, Math.floor(msLeft / 1000));
                                }
                                var s = calcLeft();
                                var resend = document.getElementById('email-change-resend');
                                var cancel = document.getElementById('email-change-cancel');
                                var card = document.getElementById('email-change-card');
                                var note = document.getElementById('email-change-note');
                                var redClasses = 'mt-3 rounded-lg border border-red-600 bg-red-50 p-3 text-red-700 dark:border-red-700 dark:bg-red-900/30 dark:text-red-200';
                                var yellowClasses = 'mt-3 rounded-lg border border-yellow-300 bg-yellow-50 p-3 text-yellow-900 dark:border-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-200';
                                function step(){
                                    if (s <= 0) {
                                        el.textContent = 'Expired';
                                        if (cancel) cancel.disabled = false;
                                        if (resend) resend.disabled = false;
                                        if (card) card.className = redClasses;
                                        if (note) note.textContent = 'Link expired. Resend to get a new verification link or cancel.';
                                        return;
                                    }
                                    s = calcLeft();
                                    var m = Math.floor(s / 60);
                                    var sec = s % 60;
                                    if (m > 60) m = 60;
                                    el.textContent = String(m).padStart(2, '0') + ':' + String(sec).padStart(2, '0');
                                    setTimeout(step, 1000);
                                }
                                step();
                            })();
                        </script>
                    </div>
                @endif

                @if ($this->hasUnverifiedEmail)
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="space-y-2">
                <flux:label>{{ __('Bio') }}</flux:label>
                <textarea
                    wire:model="bio"
                    dir="auto"
                    rows="3"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    placeholder="{{ __('Tell people about yourself') }}"
                ></textarea>
                @error('bio')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <flux:input wire:model="profile_url" :label="__('Profile URL')" type="url" placeholder="https://t.me/h3dev" />
                    @error('profile_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <flux:input wire:model="profile_link_title" :label="__('Profile Link Title')" type="text" placeholder="{{ __('e.g. Telegram') }}" />
                    @error('profile_link_title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>


            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full" data-test="update-profile-button">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <livewire:pages::settings.delete-user-form />
        @endif
    </x-pages::settings.layout>
</section>

@php
$status = $status ?? (session('status') ?? null);
if ($status === 'email-change-expired') {
    $title = __('Email change link expired');
    $desc = __('Your email change confirmation link has expired. Please request a new link from your account settings.');
}
@endphp
@include('pages.errors-container', ['code' => 403, 'title' => $title ?? null, 'desc' => $desc ?? null])

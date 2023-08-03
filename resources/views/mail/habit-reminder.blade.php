<x-mail.notification-layout>
    <x-slot:header>
        <a href="{{$url}}">
            Click here to tick them off
        </a>
    </x-slot:header>

    {{-- Footer --}}
    <x-slot:footer>
        <div>
            © {{ date('Y') }} Web Studios. @lang('All rights reserved.')
        </div>
    </x-slot:footer>
</x-mail.notification-layout>
